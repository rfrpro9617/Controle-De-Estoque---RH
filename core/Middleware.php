<?php

namespace Core;

// Contract, all middleware classes must implement this interface and has handle method
// In the future LoggingMiddleware, CachingMiddleware, CsrfGuard, etc. can be implemented and used in the router
interface Middleware
{
  public function handle(): void;
}
