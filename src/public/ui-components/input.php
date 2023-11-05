<?php

function input(string $htmlName, string $label, mixed $value = null, string $fieldType = "text", bool $required = false): string
{
    $requiredAttribute = $required ? 'required' : '';
    return <<<HTML
<div class="w-full px-2 mb-6 md:mb-0">
    <label for="$htmlName" class="block text-gray-700 text-sm font-bold mb-2">$label</label>
    <input 
        id="$htmlName"
        name="$htmlName" 
        value="$value" 
        type="$fieldType" 
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none ring-orange-500 focus:ring"
        $requiredAttribute
    />
</div>
HTML;
}
