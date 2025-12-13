<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6;
$offset = ($page - 1) * $per_page;

// Get total count
$total_stmt = $pdo->query("SELECT COUNT(*) FROM activities WHERE status = 'active'");
$total_activities = $total_stmt->fetchColumn();
$total_pages = ceil($total_activities / $per_page);

// Fetch activities with pagination
$stmt = $pdo->prepare("SELECT * FROM activities WHERE status = 'active' ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$activities = $stmt->fetchAll();

$page_title = "Our Activities";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-activities">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="animate-fade-down">Our Activities</h1>
            <p class="animate-fade-up">Making a difference in the lives of those in need</p>
        </div>
    </div>
</section>

<!-- Activities Section -->
<section class="activities-section py-5">
    <div class="container">
        <?php if (empty($activities)): ?>
            <div class="empty-state text-center py-5">
                <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                <h3>No Activities Yet</h3>
                <p class="text-muted">Check back soon for updates on our humanitarian work.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($activities as $index => $activity): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="activity-card animate-card" style="animation-delay: <?= $index * 0.1 ?>s">
                            <?php if ($activity['image_path']): ?>
                                <div class="activity-image-wrapper">
                                    <!-- FIXED: Image path from database is already uploads/activities/filename.jpg -->
                                    <!-- Frontend pages are at root level, so no ../ needed -->
                                    <img src="<?= htmlspecialchars($activity['image_path']) ?>" 
                                         alt="<?= htmlspecialchars($activity['title']) ?>"
                                         onerror="this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=300&fit=crop'">
                                    <?php if ($activity['is_featured']): ?>
                                        <span class="featured-badge">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="activity-image-wrapper">
                                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=300&fit=crop" 
                                         alt="<?= htmlspecialchars($activity['title']) ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="activity-card-body">
                                <div class="activity-category">
                                    <i class="fas fa-tag"></i>
                                    <?= htmlspecialchars(ucfirst($activity['category'])) ?>
                                </div>
                                
                                <h3 class="activity-title">
                                    <?= htmlspecialchars($activity['title']) ?>
                                </h3>
                                
                                <p class="activity-description">
                                    <?= htmlspecialchars(substr($activity['description'], 0, 150)) ?>
                                    <?= strlen($activity['description']) > 150 ? '...' : '' ?>
                                </p>
                                
                                <div class="activity-meta">
                                    <span class="activity-date">
                                        <i class="far fa-calendar"></i>
                                        <?= date('M d, Y', strtotime($activity['created_at'])) ?>
                                    </span>
                                </div>
                                
                                <button class="btn-read-more" onclick="showActivityDetails(<?= $activity['id'] ?>)">
                                    Read More
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Activity Details Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showActivityDetails(activityId) {
    const modal = new bootstrap.Modal(document.getElementById('activityModal'));
    modal.show();
    
    // Fetch activity details
    fetch(`includes/get_activity.php?id=${activityId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const activity = data.activity;
                document.getElementById('modalTitle').textContent = activity.title;
                
                let imageHtml = '';
                if (activity.image_path) {
                    // Image path is already correct from database
                    imageHtml = `<img src="${activity.image_path}" class="img-fluid rounded mb-3" alt="${activity.title}">`;
                }
                
                document.getElementById('modalBody').innerHTML = `
                    ${imageHtml}
                    <div class="mb-3">
                        <span class="badge bg-primary">${activity.category}</span>
                        <span class="text-muted ms-2">
                            <i class="far fa-calendar"></i> 
                            ${new Date(activity.created_at).toLocaleDateString()}
                        </span>
                    </div>
                    <div class="activity-full-description">
                        ${activity.description}
                    </div>
                `;
            } else {
                document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Failed to load activity details.
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('modalBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Error loading activity details.
                </div>
            `;
        });
}
</script>

<?php include 'includes/footer.php'; ?>