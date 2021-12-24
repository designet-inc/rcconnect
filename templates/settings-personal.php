<?php
/*
style('external', 'style');
script('external', 'dist/admin');
script('settings', 'apps');
script('external', 'templates');
 */
script('rcconnect', 'settings-personal');

/** @var array $_ */
/** @var \OCP\IL10N $l */
?>
<div id="rcconnect">
    <div class="section">
        <h2><?php p($l->t('RcConnect'));?></h2>
        <div><?php p($l->t('Please input username and password for RcConnect.'));?></div>
        <span id="error-msg" class="msg success hidden">Saved</span>
        <form id="rcconnect" method="POST">
            <div>
                <label for="username"><?php p($l->t('Username')); ?>: </label>
            </div>
            <div>
                <input type="hidden" id="hidden_data" name="hidden_data"
                       autocapitalize="none" autocorrect="off" />
                <input type="text" id="username" name="username"
                       placeholder="<?php p($l->t('Username'));?>"
                       autocapitalize="none" autocorrect="off" />
            </div>
            <div>
                <label for="password"><?php p($l->t('Password'));?>: </label>
            </div>
            <div>
                <input type="password" id="password" name="password"
                       placeholder="<?php p($l->t('Password')); ?>"
                       autocapitalize="none" autocorrect="off" />
            </div>
            <input id="apply" type="button" value="<?php p($l->t('Apply')); ?>" />
            <input id="delete" type="button" value="<?php p($l->t('Delete')); ?>" />
        </form>
    </div>
</div>
