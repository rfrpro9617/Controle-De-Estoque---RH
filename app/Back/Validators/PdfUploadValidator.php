<?php

namespace App\Back\Validators;

final class PdfUploadValidator
{
  private const MAX_PDF_SIZE = 10 * 1024 * 1024;

  /**
   * @return array{valid: true, temporaryPath: string}|array{valid: false, message: string}
   */
  public function validatePdfUpload(array $files): array
  {
    $file = $files['pdf'] ?? null;
    if (!is_array($file) || !isset($file['error']) || !is_int($file['error'])) {
      return [
        'valid' => false,
        'message' => 'Selecione um arquivo PDF para continuar.',
      ];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
      return [
        'valid' => false,
        'message' => match ($file['error']) {
          UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o limite de tamanho permitido.',
          UPLOAD_ERR_NO_FILE => 'Selecione um arquivo PDF para continuar.',
          default => 'Não foi possível receber o arquivo. Tente novamente.',
        },
      ];
    }

    $temporaryPath = $file['tmp_name'] ?? '';
    $fileName = $file['name'] ?? '';
    if (
      !is_string($temporaryPath)
      || $temporaryPath === ''
      || !is_uploaded_file($temporaryPath)
      || !is_string($fileName)
      || strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) !== 'pdf'
    ) {
      return [
        'valid' => false,
        'message' => 'Envie um arquivo PDF válido.',
      ];
    }

    $fileSize = filesize($temporaryPath);
    if ($fileSize === false || $fileSize === 0 || $fileSize > self::MAX_PDF_SIZE) {
      return [
        'valid' => false,
        'message' => 'O PDF deve ter conteúdo e não pode exceder 10 MB.',
      ];
    }

    $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
    if ($mimeType !== 'application/pdf') {
      return [
        'valid' => false,
        'message' => 'O arquivo enviado não foi reconhecido como PDF.',
      ];
    }

    return [
      'valid' => true,
      'temporaryPath' => $temporaryPath,
    ];
  }
}
