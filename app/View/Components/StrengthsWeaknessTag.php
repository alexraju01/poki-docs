<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StrengthsWeaknessTag extends Component
{

    public function __construct(public string $title,
    public array $data,
    public array $processedData = []
    ) {
        $this->processedData = $this->processData();
    }

    protected function processData() {
        $processed = [];
        $damageDirection = $this->title === 'strengths' ? 'double_damage_to' : 'double_damage_from';

        foreach ($this->data as $typeName => $typeData) {
            if (isset($typeData[$damageDirection])) {
                foreach ($typeData[$damageDirection] as $damageType) {
                    $processed[$typeName][] = ucfirst($damageType);
                }
            }
        }
        return $processed;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.strengths-weakness-tag');
    }
}
