<?php

namespace Stackflows\StackflowsPlugin\Bpmn\Outputs;

abstract class AbstractExternalTaskOutput implements ExternalTaskOutputInterface
{
    private array $variables;

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    public function addVariable(string $key, $value): self
    {
        $this->variables[$key] = $value;

        return $this;
    }

    public function reset()
    {
        $this->variables = [];
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }
}
