<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$items_per_page = 9;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

$stmt = $pdo->query("SELECT COUNT(*) FROM activities WHERE status = 'active'");
$total_items = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM activities WHERE status = 'active' ORDER BY activity_date DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$activities = $stmt->fetchAll();

$pagination = paginate($total_items, $items_per_page, $current_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Activities - AR-Rahma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }
        
        .section, .hero-section {
            position: relative;
            z-index: 1;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 95, 45, 0.8) 0%, rgba(33, 82, 39, 0.8) 100%);
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 4rem 0;
        }
        
        .hero-section h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
            font-size: 3rem;
        }
        
        .hero-section .lead {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .hero-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: bounce 3s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Activity Cards */
        .card {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .activity-image-wrapper {
            height: 200px;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }
        
        .activity-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .card:hover .activity-image {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            color: #2C5F2D;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .card-text {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        /* Activity Details */
        .activity-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .activity-date i {
            color: #2C5F2D;
        }
        
        .activity-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .activity-location i {
            color: #E8491D;
        }
        
        /* Badge */
        .badge-primary {
            background-color: #2C5F2D;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Button */
        .btn-primary {
            background-color: #2C5F2D;
            border-color: #2C5F2D;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-primary:hover {
            background-color: #1e3c1e;
            border-color: #1e3c1e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 95, 45, 0.3);
        }
        
        /* Empty State */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
        }
        
        .empty-icon {
            font-size: 5rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        .empty-title {
            color: #666;
            font-weight: 600;
        }
        
        /* Pagination */
        .pagination-wrapper {
            margin-top: 3rem;
        }
        
        .pagination .page-link {
            color: #2C5F2D;
            border: 1px solid #dee2e6;
            margin: 0 0.25rem;
            border-radius: 5px;
            padding: 0.5rem 1rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #2C5F2D;
            border-color: #2C5F2D;
            color: white;
        }
        
        .pagination .page-link:hover {
            background-color: rgba(44, 95, 45, 0.1);
            border-color: #2C5F2D;
        }
        
        /* Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, rgba(44, 95, 45, 0.9) 0%, rgba(33, 82, 39, 0.9) 100%);
            border-bottom: none;
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-title {
            color: white;
            font-weight: 600;
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
        }
        
        /* Activity Grid */
        .activities-grid {
            display: grid;
            gap: 2rem;
        }
        
        /* Animation for cards */
        .animate-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        /* Text Colors */
        .text-muted {
            color: #6c757d !important;
        }
        
        /* Icons */
        .bi-geo-alt, .bi-calendar {
            margin-right: 5px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .hero-icon {
                font-size: 3rem;
            }
            
            .hero-section {
                padding: 3rem 0;
            }
            
            .activity-image-wrapper {
                height: 180px;
            }
            
            .card-body {
                padding: 1.25rem;
            }
            
            .activities-grid {
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section h1 {
                font-size: 1.75rem;
            }
            
            .hero-icon {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 2.5rem 0;
            }
            
            .activity-image-wrapper {
                height: 160px;
            }
            
            .card {
                margin-bottom: 1.5rem;
            }
            
            .activities-grid {
                gap: 1rem;
            }
            
            .pagination .page-link {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section hero-activities">
        <div class="container text-center hero-content">
            <div class="hero-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <h1 class="hero-title">Our Activities</h1>
            <p class="hero-subtitle">Discover the impact we're making in our community</p>
        </div>
    </section>

    <!-- Activities Grid Section -->
    <section class="section">
        <div class="container">
            <?php if (empty($activities)): ?>
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox empty-icon"></i>
                <h4 class="mt-3 empty-title">No activities found</h4>
                <p class="text-muted">Check back soon for upcoming activities.</p>
            </div>
            <?php else: ?>
            <div class="row activities-grid">
                <?php foreach ($activities as $index => $activity): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card activity-card h-100 animate-card" data-index="<?php echo $index; ?>">
                        <div class="activity-image-wrapper">
                            <img src="<?php echo htmlspecialchars($activity['image_url'] ?? 'assets/images/placeholder.jpg'); ?>" 
                                 class="card-img-top activity-image" alt="<?php echo htmlspecialchars($activity['title']); ?>"
                                 onerror="this.src='assets/images/placeholder.jpg'">
                        </div>
                        <div class="card-body">
                            <div class="activity-date">
                                <i class="bi bi-calendar"></i> 
                                <span class="text-muted"><?php echo formatDate($activity['activity_date']); ?></span>
                            </div>
                            <h5 class="card-title activity-title mt-2"><?php echo htmlspecialchars($activity['title']); ?></h5>
                            <p class="card-text activity-description"><?php echo truncateText($activity['description'], 120); ?></p>
                            <div class="activity-location mb-2">
                                <i class="bi bi-geo-alt"></i> 
                                <span><?php echo htmlspecialchars($activity['location']); ?></span>
                            </div>
                            <?php if ($activity['beneficiaries']): ?>
                            <span class="badge badge-primary activity-badge">
                                <i class="bi bi-people"></i> <?php echo $activity['beneficiaries']; ?> beneficiaries
                            </span>
                            <?php endif; ?>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm activity-details-btn" data-activity-id="<?php echo $activity['id']; ?>"
                                        data-bs-toggle="modal" data-bs-target="#activityModal">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="mt-4 pagination-wrapper">
                <?php echo generatePaginationHTML($pagination['total_pages'], $pagination['current_page'], 'activities.php'); ?>
            </div>
            <?php endif; ?>
            
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Activity Modal -->
    <div class="modal fade" id="activityModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content activity-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityModalTitle">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="activityModalBody"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Animate cards on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.animate-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = entry.target.dataset.index * 0.1;
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, delay * 1000);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            cards.forEach(card => {
                observer.observe(card);
            });
            
            // Handle activity modal
            const activityModal = document.getElementById('activityModal');
            if (activityModal) {
                activityModal.addEventListener('show.bs.modal', async function(event) {
                    const button = event.relatedTarget;
                    const activityId = button.getAttribute('data-activity-id');
                    
                    try {
                        const response = await fetch(`api/get_activity.php?id=${activityId}`);
                        const activity = await response.json();
                        
                        if (activity) {
                            document.getElementById('activityModalTitle').textContent = activity.title;
                            
                            let modalBody = `
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <img src="${activity.image_url || 'assets/images/placeholder.jpg'}" 
                                             alt="${activity.title}" 
                                             class="img-fluid rounded"
                                             onerror="this.src='assets/images/placeholder.jpg'">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-primary"><i class="bi bi-calendar me-2"></i>Date</h6>
                                        <p>${formatDate(activity.activity_date)}</p>
                                        
                                        <h6 class="text-primary"><i class="bi bi-geo-alt me-2"></i>Location</h6>
                                        <p>${activity.location}</p>
                                        
                                        ${activity.beneficiaries ? `
                                        <h6 class="text-primary"><i class="bi bi-people me-2"></i>Beneficiaries</h6>
                                        <p>${activity.beneficiaries} people</p>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6 class="text-primary">Description</h6>
                                    <p>${activity.description}</p>
                                </div>
                            `;
                            
                            document.getElementById('activityModalBody').innerHTML = modalBody;
                        }
                    } catch (error) {
                        console.error('Error loading activity:', error);
                        document.getElementById('activityModalBody').innerHTML = 
                            '<p class="text-danger">Error loading activity details. Please try again.</p>';
                    }
                });
            }
            
            // Helper function to format date
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            }
        });
    </script>
</body>
</html>