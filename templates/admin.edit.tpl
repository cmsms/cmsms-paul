{if $question->getId()}
    <h1>Edit question</h1>
{else}
    <h1>New question</h1>
{/if}

{if isset($form)}
    {$form->render()}
{/if}
