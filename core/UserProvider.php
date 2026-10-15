<?php

namespace Core;

interface UserProvider
{
  public function findById(int $id): ?object;
}
