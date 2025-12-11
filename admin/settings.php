<?php
require_once '../config/database.php';
include 'includes/admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'submit') {
            updateSiteSetting($key, cleanInput($value));
        }
    }
    logAdminActivity(getCurrentAdminId(), 'update', 'settings', null, 'Updated site settings');
    setFlashMessage('success', 'Settings updated');
    header('Location: settings.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM site_settings");
$settings = $stmt->fetchAll();
$settings_array = [];
foreach ($settings as $setting) {
    $settings_array[$setting['setting_key']] = $setting['setting_value'];
}
?>
<h2>Site Settings</h2>
<?php displayFlashMessage(); ?>
<div class="card">
    <div class="card-body">
        <form method="POST">
            <h5>General Information</h5>
            <div class="mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?php echo $settings_array['site_name'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Site Description</label>
                <textarea name="site_description" class="form-control" rows="2"><?php echo $settings_array['site_description'] ?? ''; ?></textarea>
            </div>
            <h5 class="mt-4">Contact Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="contact_email" class="form-control" value="<?php echo $settings_array['contact_email'] ?? ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="<?php echo $settings_array['contact_phone'] ?? ''; ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="contact_address" class="form-control" value="<?php echo $settings_array['contact_address'] ?? ''; ?>">
            </div>
            <h5 class="mt-4">Social Media</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control" value="<?php echo $settings_array['facebook_url'] ?? ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" name="twitter_url" class="form-control" value="<?php echo $settings_array['twitter_url'] ?? ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control" value="<?php echo $settings_array['instagram_url'] ?? ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-control" value="<?php echo $settings_array['linkedin_url'] ?? ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
