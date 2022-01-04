<?php

namespace Filament\Tables\Actions;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;

class BulkAction
{
    use Concerns\BelongsToTable;
    use Concerns\CanBeHidden;
    use Concerns\CanBeMounted;
    use Concerns\CanDeselectRecordsAfterCompletion;
    use Concerns\CanOpenModal;
    use Concerns\CanRequireConfirmation;
    use Concerns\EvaluatesClosures;
    use Concerns\HasAction;
    use Concerns\HasColor;
    use Concerns\HasFormSchema;
    use Concerns\HasIcon;
    use Concerns\HasLabel;
    use Concerns\HasName;
    use Concerns\HasRecords;
    use Conditionable;
    use Macroable;
    use Tappable;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        $static = new static($name);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
    }

    public function call(array $data = [])
    {
        if ($this->isHidden()) {
            return;
        }

        $action = $this->getAction();

        if (! $action) {
            return;
        }

        try {
            return $this->evaluate($action, [
                'data' => $data,
            ]);
        } finally {
            if ($this->shouldDeselectRecordsAfterCompletion()) {
                $this->getLivewire()->deselectAllTableRecords();
            }
        }
    }
}
