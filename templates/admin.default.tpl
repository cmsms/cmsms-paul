{if $questions|@count > 0}
    <h1>Questions</h1>

    {if $questions|@count > 15}
        <a href="{$add}">Add a new question</a>
    {/if}

    <table class="pagetable">
        <thead>
        <tr>
            <th>Question</th>
            <th>Multiple</th>
            <th>Answers</th>
            <th class="pageincon"></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$questions item=question}
            <tr>
                <td><a href="{$question->actions.edit}">{$question->getName()}</a></td>
                <td>{if $question->getIsMultiple()}true{else}false{/if}</td>
                <td>{$question->countAnswers()}</td>
                <td>
                    <a href="{$question->actions.edit}">Edit</a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}

<a href="{$add}">Add a new question</a>