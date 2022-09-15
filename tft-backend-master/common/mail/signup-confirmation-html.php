<?php
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user app\models\User */
    /* @var $appName string */
    /* @var $confirmURL string */

?>
<div style="">
            <h1 style="color: #F8C63E ; font-family:'Roboto', sans-serif; font-size: 24px; padding: 20px 0 0 50px;">Hello dishant,</h1>
</div>
<div class="data-mid" style="padding: 0 0 40px 0;">
    <p style="font-family:'Roboto', sans-serif; font-size: 16px; letter-spacing: 0.5px; color: #484848; text-align: center; line-height:24px; padding: 0 50px;">
        <?=$message?>
    </p>
</div>
<table border="0" cellpadding="18" cellspacing="0" class="mcnTextContentContainer" width="100%" style="background-color: #FFFFFF;">
    <tbody>
    <tr>
        <td valign="top" class="mcnTextContent" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: left; padding: 36px; word-break: break-word;">
            <div style="text-align: center; margin-bottom: 36px">
                <?=$appName;?>
            </div>
            <div style="text-align: left; word-wrap: break-word;">Thank you for joining <?=$appName;?>! To finish signing up, you just need to confirm that we got your email right.
                <br />
                <br />To confirm your email, please click this link:
                <br /><br />
                <?=$code;?>
                <br />
                <br />Welcome and thanks!
                <br />The Team
                <div class="footer" style="font-size: 0.7em; padding: 0px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: right; color: #777777; line-height: 14px; margin-top: 36px;">© <?=date("Y");?> Company
                    <br>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>