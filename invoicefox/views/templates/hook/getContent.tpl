{if isset($confirmation)}
{if isset($confirmation) && $confirmation=='ok'}
    <div class="alert alert-success">{l s='Settings updated' mod='invoicfox'}</div>
{else}
    <div class="alert alert-warning">{$msg}</div>
{/if}
{/if}