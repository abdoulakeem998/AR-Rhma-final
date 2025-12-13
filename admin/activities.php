<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireAdmin();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ============================================
// CHEMINS ABSOLUMENT CORRECTS
// ============================================

// 1. VOTRE SITE WEB (URL - pour navigateurs)
$base = '/~ngoila.karimou/uploads/AR-Rhma-final/';
$admin_base = $base . 'admin/';

// 2. CHEMINS SERVEUR (disque dur - pour PHP)
// OPTION 1: Créer un nouveau dossier 'uploaded_images'
$upload_base_dir = '/home/ngoila.karimou/public_html/uploads/AR-Rhma-final/uploaded_images/';
$upload_url_path = '/~ngoila.karimou/uploads/AR-Rhma-final/uploaded_images/';

// OPTION 2: Utiliser 'uploads' (si existe)
// $upload_base_dir = '/home/ngoila.karimou/public_html/uploads/AR-Rhma-final/uploads/';
// $upload_url_path = '/~ngoila.karimou/uploads/AR-Rhma-final/uploads/';

// ============================================
// CRÉATION AUTOMATIQUE DU DOSSIER
// ============================================
if (!file_exists($upload_base_dir)) {
    // Essayer de créer le dossier
    if (mkdir($upload_base_dir, 0777, true)) {
        chmod($upload_base_dir, 0777);
        echo '<div class="alert alert-success">✅ Dossier créé: ' . $upload_base_dir . '</div>';
    } else {
        echo '<div class="alert alert-danger">';
        echo '❌ Impossible de créer le dossier automatiquement.<br>';
        echo 'Veuillez le créer manuellement:<br>';
        echo '1. File Manager → <code>/home/ngoila.karimou/public_html/uploads/AR-Rhma-final/</code><br>';
        echo '2. Créez dossier: <code>uploaded_images</code><br>';
        echo '3. Permissions: <code>777</code>';
        echo '</div>';
        $dir_error = true;
    }
}

// Vérifier les permissions
if (file_exists($upload_base_dir) && !is_writable($upload_base_dir)) {
    // Essayer de changer les permissions
    if (chmod($upload_base_dir, 0777)) {
        echo '<div class="alert alert-warning">⚠️ Permissions changées à 777</div>';
    } else {
        echo '<div class="alert alert-danger">❌ Dossier non écrivable. Changez permissions à 777</div>';
        $dir_error = true;
    }
}

// ============================================
// GESTION SUPPRESSION
// ============================================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_url FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        $activity = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete image file if exists
            if ($activity && $activity['image_url']) {
                // Convertir URL web en chemin serveur
                $web_path = $activity['image_url'];
                $server_path = '/home/ngoila.karimou/public_html' . $web_path;
                
                if (file_exists($server_path)) {
                    unlink($server_path);
                }
            }
            
            logAdminActivity(getCurrentAdminId(), 'delete', 'activity', $id, 'Deleted activity');
            setFlashMessage('success', 'Activity deleted successfully');
        }
    } catch (Exception $e) {
        setFlashMessage('danger', 'Error deleting activity: ' . $e->getMessage());
    }
    redirect($admin_base . 'activities.php');
}

// ============================================
// GESTION CRÉATION/MODIFICATION
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $date = $_POST['activity_date'];
    $location = cleanInput($_POST['location']);
    $beneficiaries = (int)$_POST['beneficiaries'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    $image_url = '';
    $upload_error = '';
    
    // GESTION UPLOAD IMAGE
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        // Vérifier erreurs d'upload
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => 'Fichier trop grand (max php.ini)',
                UPLOAD_ERR_FORM_SIZE => 'Fichier trop grand (max formulaire)',
                UPLOAD_ERR_PARTIAL => 'Fichier partiellement uploadé',
                UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
                UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire sur disque',
                UPLOAD_ERR_EXTENSION => 'Extension PHP a arrêté l\'upload'
            ];
            $upload_error = $upload_errors[$_FILES['image']['error']] ?? 'Erreur inconnue';
        } else {
            // Validation du fichier
            $file_info = [
                'name' => $_FILES['image']['name'],
                'type' => $_FILES['image']['type'],
                'tmp_name' => $_FILES['image']['tmp_name'],
                'size' => $_FILES['image']['size']
            ];
            
            // Vérifier type MIME réel
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $actual_mime = finfo_file($finfo, $file_info['tmp_name']);
            finfo_close($finfo);
            
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($actual_mime, $allowed_types)) {
                $upload_error = 'Type de fichier invalide. Seuls JPG, PNG, GIF sont autorisés. Type détecté: ' . $actual_mime;
            } elseif ($file_info['size'] > $max_size) {
                $upload_error = 'Fichier trop volumineux. Maximum: 5MB. Votre fichier: ' . round($file_info['size'] / 1024 / 1024, 2) . 'MB';
            } elseif ($file_info['size'] == 0) {
                $upload_error = 'Fichier vide (0 bytes)';
            } else {
                // Créer nom de fichier unique
                $extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
                $filename = 'activity_' . time() . '_' . uniqid() . '.' . $extension;
                
                // CHEMIN SERVEUR pour sauvegarde
                $filepath = $upload_base_dir . $filename;
                
                // Tenter l'upload
                if (move_uploaded_file($file_info['tmp_name'], $filepath)) {
                    chmod($filepath, 0644);
                    
                    // CHEMIN WEB pour affichage
                    $image_url = $upload_url_path . $filename;
                    
                    // Supprimer ancienne image si modification
                    if ($id > 0) {
                        $stmt = $pdo->prepare("SELECT image_url FROM activities WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_activity = $stmt->fetch();
                        if ($old_activity && $old_activity['image_url']) {
                            $old_web_path = $old_activity['image_url'];
                            $old_server_path = '/home/ngoila.karimou/public_html' . $old_web_path;
                            if (file_exists($old_server_path)) {
                                unlink($old_server_path);
                            }
                        }
                    }
                } else {
                    $upload_error = 'Impossible de déplacer le fichier. Vérifiez permissions du dossier. Chemin: ' . $filepath;
                }
            }
        }
    }
    
    // SAUVEGARDER DANS BASE DE DONNÉES
    if (empty($upload_error)) {
        try {
            if ($id > 0) {
                // Mise à jour
                if ($image_url) {
                    $stmt = $pdo->prepare("UPDATE activities SET title=?, description=?, activity_date=?, location=?, beneficiaries=?, category=?, status=?, featured=?, image_url=? WHERE id=?");
                    $stmt->execute([$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $image_url, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE activities SET title=?, description=?, activity_date=?, location=?, beneficiaries=?, category=?, status=?, featured=? WHERE id=?");
                    $stmt->execute([$title, $description, $date, $location, $beneficiaries, $category, $status, $featured, $id]);
                }
                logAdminActivity(getCurrentAdminId(), 'update', 'activity', $id, 'Updated activity');
                setFlashMessage('success', 'Activity updated successfully');
            } else {
                // Création
                $stmt = $pdo->prepare("INSERT INTO activities (title, description, activity_date, location, beneficiaries, image_url, category, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $date, $location, $beneficiaries, $image_url, $category, $status, $featured, getCurrentAdminId()]);
                logAdminActivity(getCurrentAdminId(), 'create', 'activity', $pdo->lastInsertId(), 'Created activity');
                setFlashMessage('success', 'Activity created successfully' . ($image_url ? ' with image' : ''));
            }
            redirect($admin_base . 'activities.php');
        } catch (Exception $e) {
            setFlashMessage('danger', 'Database error: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('danger', $upload_error);
    }
}

// ============================================
// RÉCUPÉRER ACTIVITÉS
// ============================================

// Get activity for editing
$edit_activity = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_activity = $stmt->fetch();
}

// Get all activities
$stmt = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC");
$activities = $stmt->fetchAll();

include 'includes/admin_header.php';
?>

<!-- Display upload directory error if creation failed -->
<?php if (isset($dir_error)): ?>
<div class="alert alert-danger">
    <strong>⚠️ Erreur Dossier Upload!</strong><br>
    Le dossier d'upload ne peut pas être créé automatiquement. Créez-le manuellement:<br>
    <code><?php echo $upload_base_dir; ?></code><br><br>
    <strong>Étapes:</strong><br>
    1. Utilisez File Manager<br>
    2. Allez à: <code>/home/ngoila.karimou/public_html/uploads/AR-Rhma-final/</code><br>
    3. Créez dossier: <code>uploaded_images</code><br>
    4. Permissions: <code>777</code>
</div>
<?php endif; ?>

<!-- Display upload directory info for debugging -->
<?php if (isset($_GET['debug'])): ?>
<div class="alert alert-info">
    <strong>Info Débogage:</strong><br>
    Dossier Upload Serveur: <?php echo $upload_base_dir; ?><br>
    URL Upload Web: <?php echo $upload_url_path; ?><br>
    Dossier Existe: <?php echo file_exists($upload_base_dir) ? 'Oui' : 'Non'; ?><br>
    Dossier Écrivable: <?php echo is_writable($upload_base_dir) ? 'Oui' : 'Non'; ?><br>
    PHP upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?><br>
    PHP post_max_size: <?php echo ini_get('post_max_size'); ?>
</div>
<?php endif; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-event"></i> Gérer Activités</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activityModal">
            <i class="bi bi-plus-circle"></i> Nouvelle Activité
        </button>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Bénéficiaires</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>En vedette</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Aucune activité. Créez votre première activité!</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td>
                                <?php if ($activity['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($activity['image_url']); ?>" 
                                     alt="Activity" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 1.5rem; color: #adb5bd;"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($activity['title']); ?></strong>
                            </td>
                            <td><?php echo formatDate($activity['activity_date']); ?></td>
                            <td><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($activity['location']); ?></td>
                            <td><i class="bi bi-people"></i> <?php echo $activity['beneficiaries']; ?></td>
                            <td><span class="badge bg-info"><?php echo ucwords(str_replace('_', ' ', $activity['category'])); ?></span></td>
                            <td>
                                <?php 
                                $status_class = $activity['status'] === 'active' ? 'success' : ($activity['status'] === 'draft' ? 'warning' : 'secondary');
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($activity['status']); ?></span>
                            </td>
                            <td>
                                <?php if ($activity['featured']): ?>
                                <i class="bi bi-star-fill text-warning" title="Featured"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?delete=<?php echo $activity['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité?')"
                                   title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter/Modifier -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-<?php echo $edit_activity ? 'pencil' : 'plus-circle'; ?>"></i>
                    <?php echo $edit_activity ? 'Modifier' : 'Nouvelle'; ?> Activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="activityForm">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $edit_activity['id'] ?? 0; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($edit_activity['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($edit_activity['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="activity_date" class="form-control" required 
                                   value="<?php echo $edit_activity['activity_date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lieu *</label>
                            <input type="text" name="location" class="form-control" required 
                                   value="<?php echo htmlspecialchars($edit_activity['location'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bénéficiaires</label>
                            <input type="number" name="beneficiaries" class="form-control" min="0"
                                   value="<?php echo $edit_activity['beneficiaries'] ?? 0; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select name="category" class="form-control" required>
                                <option value="orphan_support" <?php echo ($edit_activity['category'] ?? '') === 'orphan_support' ? 'selected' : ''; ?>>Soutien Orphelins</option>
                                <option value="disability_care" <?php echo ($edit_activity['category'] ?? '') === 'disability_care' ? 'selected' : ''; ?>>Soins Handicapés</option>
                                <option value="poverty_relief" <?php echo ($edit_activity['category'] ?? '') === 'poverty_relief' ? 'selected' : ''; ?>>Lutte Pauvreté</option>
                                <option value="education" <?php echo ($edit_activity['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Éducation</option>
                                <option value="healthcare" <?php echo ($edit_activity['category'] ?? '') === 'healthcare' ? 'selected' : ''; ?>>Santé</option>
                                <option value="emergency_relief" <?php echo ($edit_activity['category'] ?? '') === 'emergency_relief' ? 'selected' : ''; ?>>Urgence</option>
                                <option value="other" <?php echo ($edit_activity['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Statut *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" <?php echo ($edit_activity['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="draft" <?php echo ($edit_activity['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Brouillon</option>
                                <option value="archived" <?php echo ($edit_activity['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archivée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image Activité</label>
                        <?php if ($edit_activity && $edit_activity['image_url']): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($edit_activity['image_url']); ?>" 
                                 alt="Image actuelle" style="max-width: 200px; border-radius: 8px;">
                            <p class="text-muted small mt-1">Image actuelle (téléchargez nouvelle pour remplacer)</p>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">Types acceptés: JPG, PNG, GIF (Max 5MB)</small>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                        </div>
                        <div id="uploadInfo" class="mt-2 small text-muted"></div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="featured" class="form-check-input" id="featured"
                               <?php echo ($edit_activity['featured'] ?? false) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featured">
                            <i class="bi bi-star"></i> Mettre en vedette sur page d'accueil
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image avec info fichier
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const info = document.getElementById('uploadInfo');
    
    if (file) {
        // Afficher info fichier
        const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
        info.innerHTML = `<strong>Fichier:</strong> ${file.name}<br><strong>Taille:</strong> ${sizeInMB} MB<br><strong>Type:</strong> ${file.type}`;
        
        // Vérifier taille
        if (file.size > 5 * 1024 * 1024) {
            info.innerHTML += '<br><span class="text-danger">⚠️ Fichier trop grand! Max 5MB</span>';
            e.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Afficher preview
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        info.innerHTML = '';
    }
});

// Empêcher double soumission
document.getElementById('activityForm')?.addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sauvegarde...';
});

// Ouvrir modal si édition
<?php if ($edit_activity): ?>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('activityModal')).show();
});
<?php endif; ?>
</script>

<?php include 'includes/admin_footer.php'; ?>