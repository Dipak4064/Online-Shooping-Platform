<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Check if user already has a store
if (user_has_store($_SESSION['user']['id'])) {
    header('Location: my_store.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['store_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['store_email'] ?? '');
    
    // Validation
    if (empty($name)) {
        $error = 'Store name is required';
    } elseif (strlen($name) < 3) {
        $error = 'Store name must be at least 3 characters';
    } else {
        // Handle logo upload
        $logo = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/stores/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = 'store_' . $_SESSION['user']['id'] . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $filePath)) {
                    $logo = $filePath;
                }
            }
        }
        
        // Create store
        $storeData = [
            'name' => $name,
            'description' => $description,
            'logo' => $logo,
            'address' => $address,
            'phone' => $phone,
            'email' => $email
        ];
        
        $storeId = create_store($_SESSION['user']['id'], $storeData);
        
        if ($storeId > 0) {
            // Update session role
            $_SESSION['user']['role'] = 'store_owner';
            header('Location: my_store.php');
            exit;
        } else {
            $error = 'Failed to create store. You may already have a store or the store name is already taken.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: #e8e8e8;
            min-height: 100vh;
        }
        
        .create-store-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .store-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #ffffff;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            max-width: 1100px;
            width: 100%;
        }
        
        .form-section {
            background: #ffffff;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-content {
            width: 100%;
            max-width: 450px;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #1f2937;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }
        
        .page-header .back-btn:hover {
            color: #111827;
            transform: translateX(-3px);
        }
        
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        .store-form .form-group {
            margin-bottom: 1.25rem;
        }
        
        .store-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        
        .store-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1f2937;
            font-size: 0.875rem;
        }
        
        .store-form label .required {
            color: #dc2626;
        }
        
        .store-form input[type="text"],
        .store-form input[type="email"],
        .store-form input[type="tel"],
        .store-form textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            font-family: inherit;
            background: #ffffff;
            color: #1f2937;
        }
        
        .store-form input:focus,
        .store-form textarea:focus {
            outline: none;
            border-color: #1f2937;
            box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
        }
        
        .store-form input::placeholder,
        .store-form textarea::placeholder {
            color: #9ca3af;
        }
        
        .store-form textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .logo-upload {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .upload-preview {
            width: 70px;
            height: 70px;
            border-radius: 0.75rem;
            background: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.75rem;
            flex-shrink: 0;
        }
        
        .upload-area {
            flex: 1;
            position: relative;
            border: 1px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            background: #ffffff;
        }
        
        .upload-area:hover {
            border-color: #1f2937;
            background: #f9fafb;
        }
        
        .upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .upload-text {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .upload-text span {
            color: #1f2937;
            font-weight: 600;
        }
        
        .submit-btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: #1f2937;
            color: #ffffff;
            border: none;
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .submit-btn:hover {
            background: #111827;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 41, 55, 0.25);
        }
        
        .submit-btn i {
            font-size: 1.1rem;
        }
        
        .promo-section {
            position: relative;
            overflow: hidden;
            min-height: 500px;
        }
        
        .promo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            inset: 0;
        }
        
        .promo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.2) 50%, transparent 100%);
            display: flex;
            align-items: flex-end;
            padding: 3rem;
        }
        
        .promo-content {
            color: white;
            max-width: 350px;
        }
        
        .promo-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.75rem;
            line-height: 1.3;
        }
        
        .promo-content p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
            margin: 0 0 1.5rem;
            line-height: 1.6;
        }
        
        .promo-features {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .promo-features li {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
        }
        
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert i {
            font-size: 1.1rem;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        @media (max-width: 992px) {
            .store-container {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            
            .promo-section {
                display: none;
            }
            
            .form-section {
                padding: 2rem;
            }
        }
        
        @media (max-width: 600px) {
            .create-store-page {
                padding: 1rem;
            }
            
            .store-container {
                border-radius: 1rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .store-form .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .logo-upload {
                flex-direction: column;
                align-items: stretch;
            }
            
            .upload-preview {
                width: 60px;
                height: 60px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="create-store-page">
        <div class="store-container">
            <div class="form-section">
                <div class="form-content">
                    <div class="page-header">
                        <a href="profile.php" class="back-btn">
                            <i class="fas fa-arrow-left"></i> Back to Profile
                        </a>
                        <h1>Create Your Store</h1>
                        <p>Set up your store and start selling today</p>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo htmlspecialchars($success); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form class="store-form" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="store_name">Store Name <span class="required">*</span></label>
                                <input type="text" id="store_name" name="store_name" placeholder="Enter your store name" required 
                                       value="<?php echo htmlspecialchars($_POST['store_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="store_email">Store Email</label>
                                <input type="email" id="store_email" name="store_email" placeholder="store@example.com"
                                       value="<?php echo htmlspecialchars($_POST['store_email'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Store Description</label>
                            <textarea id="description" name="description" placeholder="Tell customers what makes your store special..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="+977 98XXXXXXXX"
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Store Address</label>
                                <input type="text" id="address" name="address" placeholder="City, Country"
                                       value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Store Logo</label>
                            <div class="logo-upload">
                                <div class="upload-preview" id="logoPreview">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="upload-area">
                                    <input type="file" name="logo" id="logo" accept="image/*">
                                    <p class="upload-text"><span>Click to upload</span> or drag and drop<br>PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-rocket"></i> Launch Your Store
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="promo-section">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=1000&fit=crop" alt="Start selling">
                <div class="promo-overlay">
                    <div class="promo-content">
                        <h2>Start Your Business Journey</h2>
                        <p>Join thousands of successful sellers and reach customers worldwide with your own store.</p>
                        <ul class="promo-features">
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Free Setup</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Secure Payments</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // File upload preview with image
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            const uploadText = this.parentElement.querySelector('.upload-text');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">`;
                };
                reader.readAsDataURL(file);
                uploadText.innerHTML = `<strong>${file.name}</strong><br><span>Click to change</span>`;
            }
        });
    </script>
</body>
</html>
