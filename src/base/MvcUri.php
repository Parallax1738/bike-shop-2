<?php
class MvcUri
{
  private string $controller;
  private string $action;
  private array $parameters;

  public function __construct(string $controller, string $action, array $parameters)
  {
    $this->controller = $controller;
    $this->action = $action;
    $this->parameters = $parameters;
  }

  public function getController(): string
  {
    return $this->controller;
  }

  public function getAction(): string
  {
    return $this->action;
  }

  public function getParameters(): array
  {
    return $this->parameters;
  }
}