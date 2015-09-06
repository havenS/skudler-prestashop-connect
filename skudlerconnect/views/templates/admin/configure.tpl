<div class="panel form-block">
    <h3><i class="icon icon-cog"></i> {l s='API Settings' mod='skudlerconnect'}</h3>
    <form method="post" class="form-horizontal">
        {if $apiStatus}
            <div class="col-xs-12">
                {l s="Your credentials are correct" mod="skudlerconnect"}
            </div>
        {/if}

        <div class="form-group">
            <label for="skudler_api_key" class="col-sm-4 control-label">
                {l s="API Key" mod="skudlerconnect"}
            </label>
            <div class="col-sm-8">
                <input type="text" id="skudler_api_key" name="SKUDLER_API_KEY" value="{$options['SKUDLER_API_KEY']}"/>
            </div>
        </div>
        <div class="form-group">
            <label for="skudler_api_key" class="col-sm-4 control-label">
                {l s="API Token" mod="skudlerconnect"}
            </label>
            <div class="col-sm-8">
                <input type="text" id="skudler_api_token" name="SKUDLER_API_TOKEN" value="{$options['SKUDLER_API_TOKEN']}"/>
            </div>
        </div>
        <input type="submit" class="btn btn-primary">
    </form>
</div>

<div class="panel form-block">
    <h3><i class="icon icon-cog"></i> {l s='Information' mod='skudlerconnect'}</h3>
    <form method="post">

        <div class="row">
            <div class="form-group">
                <label for="skudler_enabled" class="col-sm-4 control-label">
                    {l s="Enabled the plugin" mod="skudlerconnect"}
                </label>
                <div class="col-sm-8">
                    <input type="hidden" name="SKUDLER_ENABLED" value="0">
                    <input id="skudler_enabled" type="checkbox" name="SKUDLER_ENABLED" {if $options['SKUDLER_ENABLED']} checked{/if}>
                </div>
            </div>
        </div>

        {if $apiStatus}

            <script type="text/javascript">
                siteId = '{$options['SKUDLER_SITE_ID']}';
            </script>
            <div class="row">
                <div class="form-group" id="siteFields">
                    <label for="skudler_site_id" class="col-sm-4 control-label">
                        {l s="Site ID" mod="skudlerconnect"}
                    </label>
                    <div class="col-sm-8">
                        <select id="skudler_site_id" name="SKUDLER_SITE_ID">
                            {foreach $sites as $site}
                                <option value="{$site->_id}"{if $options['SKUDLER_SITE_ID'] == $site->_id} selected{/if}>{$site->name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div id="eventsLocked" style="display: none;">
                <div class="row">
                    <div class="col-xs-12">{l s="You changed the site ID, please save to fetch correct events." mod="skudlerconnect"}</div>
                </div>
            </div>

            <div id="eventsSetting">
                {if $options['SKUDLER_SITE_ID']}
                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_register_status" class="col-sm-4 control-label">
                                {l s="Enabled register event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="SKUDLER_REGISTER_STATUS" value="0">
                                <input id="skudler_register_status" type="checkbox" name="SKUDLER_REGISTER_STATUS"{if $options['SKUDLER_REGISTER_STATUS']} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_register_event" class="col-sm-4 control-label">
                                {l s="Register Event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <select id="skudler_register_event" name="SKUDLER_REGISTER_EVENT">
                                    {foreach $events as $event}
                                        <option value="{$event->_id}"{if $options['SKUDLER_REGISTER_EVENT'] == $event->_id} selected{/if}>{$event->name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_login_status" class="col-sm-4 control-label">
                                {l s="Enabled login event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="SKUDLER_LOGIN_STATUS" value="0">
                                <input id="skudler_login_status" type="checkbox" name="SKUDLER_LOGIN_STATUS"{if $options['SKUDLER_LOGIN_STATUS']} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_login_event" class="col-sm-4 control-label">
                                {l s="Login Event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <select id="skudler_login_event" name="SKUDLER_LOGIN_EVENT">
                                    {foreach $events as $event}
                                        <option value="{$event->_id}"{if $options['SKUDLER_LOGIN_EVENT'] == $event->_id} selected{/if}>{$event->name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_cart_update_status" class="col-sm-4 control-label">
                                {l s="Enabled cart update event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="SKUDLER_CART_UPDATE_STATUS" value="0">
                                <input id="skudler_cart_update_status" type="checkbox" name="SKUDLER_CART_UPDATE_STATUS"{if $options['SKUDLER_CART_UPDATE_STATUS']} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_cart_update_event" class="col-sm-4 control-label">
                                {l s="Cart update Event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <select id="skudler_cart_update_event" name="SKUDLER_CART_UPDATE_EVENT">
                                    {foreach $events as $event}
                                        <option value="{$event->_id}"{if $options['SKUDLER_CART_UPDATE_EVENT'] == $event->_id} selected{/if}>{$event->name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_new_order_status" class="col-sm-4 control-label">
                                {l s="Enabled new order event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="SKUDLER_NEW_ORDER_STATUS" value="0">
                                <input id="skudler_new_order_status" type="checkbox" name="SKUDLER_NEW_ORDER_STATUS"{if $options['SKUDLER_NEW_ORDER_STATUS']} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="skudler_new_order_event" class="col-sm-4 control-label">
                                {l s="New order Event" mod="skudlerconnect"}
                            </label>
                            <div class="col-sm-8">
                                <select id="skudler_new_order_event" name="SKUDLER_NEW_ORDER_EVENT">
                                    {foreach $events as $event}
                                        <option value="{$event->_id}"{if $options['SKUDLER_NEW_ORDER_EVENT'] == $event->_id} selected{/if}>{$event->name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                {/if}

            </div>

        {/if}
        <input type="submit" class="btn btn-primary">
    </form>
</div>