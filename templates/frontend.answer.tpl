{if isset($question)}
    <h2>{$question->getName()}</h2>
    <ul>
    {foreach from=$question->getAnswers() item=answer}
        <li>
            <strong>{$answer->getName()}</strong>
            - {$answer->countResponses()}
            - {$answer->responsesPercentage()}%
        </li>
    {/foreach}
    </ul>
{/if}