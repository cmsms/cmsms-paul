{if isset($question)}
    <h2>{$question->getName()}</h2>
    {$form->render()}
{/if}