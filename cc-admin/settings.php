<?php

// Init application
include_once(dirname(dirname(__FILE__)) . '/cc-core/system/admin.bootstrap.php');

// Verify if user is logged in
$userService = new UserService();
$adminUser = $userService->loginCheck();
Functions::RedirectIf($adminUser, HOST . '/login/');
Functions::RedirectIf($userService->checkPermissions('admin_panel', $adminUser), HOST . '/account/');

// Establish page variables, objects, arrays, etc
$page_title = 'General Settings';
$data = array();
$errors = array();
$message = null;

// Retrieve settings from database
$data['sitename'] = Settings::get('sitename');
$data['base_url'] = Settings::get('base_url');
$data['admin_email'] = Settings::get('admin_email');
$data['auto_approve_videos'] = Settings::get('auto_approve_videos');
$data['auto_approve_users'] = Settings::get('auto_approve_users');
$data['auto_approve_comments'] = Settings::get('auto_approve_comments');
$data['mobile_site'] = Settings::get('mobile_site');

// Handle form if submitted
if (isset($_POST['submitted'])) {

    // Validate sitename
    if (!empty($_POST['sitename']) && !ctype_space($_POST['sitename'])) {
        $data['sitename'] = trim($_POST['sitename']);
    } else {
        $errors['sitename'] = 'Invalid sitename';
    }

    // Validate base_url
    $pattern = '/^https?:\/\/[a-z0-9][a-z0-9\.\-]+.*$/i';
    if (!empty($_POST['base_url']) && preg_match($pattern, $_POST['base_url'])) {
        $data['base_url'] = rtrim($_POST['base_url'], '/');
    } else {
        $errors['base_url'] = 'Invalid base url';
    }

    // Validate admin_email
    $pattern = '/^[a-z0-9][a-z0-9\.\-]+@[a-z0-9][a-z0-9\.\-]+$/i';
    if (!empty($_POST['admin_email']) && preg_match($pattern, $_POST['admin_email'])) {
        $data['admin_email'] = trim($_POST['admin_email']);
    } else {
        $errors['admin_email'] = 'Invalid admin email';
    }

    // Validate auto_approve_videos
    if (isset($_POST['auto_approve_videos']) && in_array($_POST['auto_approve_videos'], array('1', '0'))) {
        $data['auto_approve_videos'] = $_POST['auto_approve_videos'];
    } else {
        $errors['auto_approve_videos'] = 'Invalid video approval option';
    }

    // Validate auto_approve_users
    if (isset($_POST['auto_approve_users']) && in_array($_POST['auto_approve_users'], array ('1', '0'))) {
        $data['auto_approve_users'] = $_POST['auto_approve_users'];
    } else {
        $errors['auto_approve_users'] = 'Invalid member approval option';
    }

    // Validate auto_approve_comments
    if (isset($_POST['auto_approve_comments']) && in_array($_POST['auto_approve_comments'], array('1', '0'))) {
        $data['auto_approve_comments'] = $_POST['auto_approve_comments'];
    } else {
        $errors['auto_approve_comments'] = 'Invalid comment approval option';
    }

    // Validate mobile site
    if (isset($_POST['mobile_site']) && in_array($_POST['mobile_site'], array('1', '0'))) {
        $data['mobile_site'] = $_POST['mobile_site'];
    } else {
        $errors['mobile_site'] = 'Invalid mobile site value';
    }

    // Update video if no errors were made
    if (empty($errors)) {
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
        $message = 'Settings have been updated.';
        $message_type = 'success';
    } else {
        $message = 'The following errors were found. Please correct them and try again.';
        $message .= '<br /><br /> - ' . implode('<br /> - ', $errors);
        $message_type = 'errors';
    }
}


// Output Header
$pageName = 'settings';
include ('header.php');

?>

<h1>General Settings</h1>

<?php if ($message): ?>
<div class="message <?=$message_type?>"><?=$message?></div>
<?php endif; ?>

<form action="<?=ADMIN?>/settings.php" method="post">

    <div class="form-group <?=(isset ($errors['sitename'])) ? ' error' : ''?>">
        <label>Sitename:</label>
        <input class="form-control" type="text" name="sitename" value="<?=$data['sitename']?>" />
    </div>

    <div class="form-group <?=(isset ($errors['base_url'])) ? ' error' : ''?>">
        <label>Base URL:</label>
        <input class="form-control" type="text" name="base_url" value="<?=$data['base_url']?>" />
    </div>

    <div class="form-group <?=(isset ($errors['admin_email'])) ? ' error' : ''?>">
        <label>Admin Email:</label>
        <input class="form-control" type="text" name="admin_email" value="<?=$data['admin_email']?>" />
    </div>

    <div class="form-group <?=(isset ($errors['auto_approve_videos'])) ? ' error' : ''?>">
        <label>Video Approval:</label>
        <select name="auto_approve_videos" class="form-control">
            <option value="1" <?=($data['auto_approve_videos']=='1')?'selected="selected"':''?>>Auto-Approve</option>
            <option value="0" <?=($data['auto_approve_videos']=='0')?'selected="selected"':''?>>Approval Required</option>
        </select>
    </div>

    <div class="form-group <?=(isset ($errors['auto_approve_users'])) ? ' error' : ''?>">
        <label>Member Approval:</label>
        <select name="auto_approve_users" class="form-control">
            <option value="1" <?=($data['auto_approve_users']=='1')?'selected="selected"':''?>>Auto-Approve</option>
            <option value="0" <?=($data['auto_approve_users']=='0')?'selected="selected"':''?>>Approval Required</option>
        </select>
    </div>

    <div class="form-group <?=(isset ($errors['auto_approve_comments'])) ? ' error' : ''?>">
        <label>Comment Approval:</label>
        <select name="auto_approve_comments" class="form-control">
            <option value="1" <?=($data['auto_approve_comments']=='1')?'selected="selected"':''?>>Auto-Approve</option>
            <option value="0" <?=($data['auto_approve_comments']=='0')?'selected="selected"':''?>>Approval Required</option>
        </select>
    </div>

    <div class="form-group <?=(isset ($errors['mobile_site'])) ? ' error' : ''?>">
        <label>Mobile Site:</label>
        <select name="mobile_site" class="form-control">
            <option value="1" <?=($data['mobile_site']=='1')?'selected="selected"':''?>>Enabled</option>
            <option value="0" <?=($data['mobile_site']=='0')?'selected="selected"':''?>>Disabled</option>
        </select>
    </div>

    <input type="hidden" name="submitted" value="TRUE" />
    <input type="submit" class="button" value="Update Settings" />
        
</form>

<?php include ('footer.php'); ?>