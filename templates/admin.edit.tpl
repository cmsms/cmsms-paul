{if $question->getId()}
    <h1>Edit question</h1>
{else}
    <h1>New question</h1>
{/if}

{if isset($form)}
    {if $form->hasErrors()}
        <div style="color: red;">{$form->showErrors()}</div>{/if}
    {$form->getHeaders()}

    {$form->showWidget('name')}
    {$form->showWidget('description')}
    {$form->showWidget('is_multiple')}
    <h2>Answers</h2>
    {if $question->getAnswers()|@count > 0}
        <table class="pagetable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Responses</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$question->getAnswers() item=answer}
                <tr>
                    <td>{$answer->getName()}</td>
                    <td>{$answer->getDescription()}</td>
                    <td>{$answer->countResponses()}</td>
                    <td><a href="{$answer->actions.delete}">delete</a></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
    <fieldset>
        <h3>Add an answer</h3>
        {$form->showWidget('answer')}
        {$form->showWidget('answer_description')}
    </fieldset>

    {$form->showWidgets()}

    {$form->renderFieldsets()}
    <p>
        {$form->getButtons()}
    </p>
    {$form->getFooters()}
{/if}