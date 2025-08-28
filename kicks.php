<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanich Collection - Premium Kicks</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation */
        .nav-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        /* Page content container */
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            min-height: calc(100vh - 80px);
        }

        /* Homepage Styles */
        .homepage {
            display: none;
        }

        .homepage.active {
            display: block;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 40px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .quick-filters {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .quick-filters h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 1.8rem;
        }

        .filter-row {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-group {
            text-align: center;
        }

        .filter-group h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .featured-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .view-all-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .view-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Products Page Styles */
        .products-page {
            display: none;
        }

        .products-page.active {
            display: block;
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .results-info {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .content-layout {
            display: flex;
            gap: 30px;
        }

        .sidebar {
            width: 320px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .filter-section {
            margin-bottom: 35px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f8f9fa;
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .filter-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .clear-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .filter-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .filter-tag .remove {
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .filter-tag .remove:hover {
            opacity: 1;
        }

        .clear-all-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .clear-all-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .stock-filter {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .stock-filter input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .stock-filter label {
            font-weight: 600;
            cursor: pointer;
            color: #495057;
        }

        .price-section {
            margin-bottom: 25px;
        }

        .price-inputs {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
        }

        .price-input {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            width: 100px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            transition: border-color 0.3s ease;
        }

        .price-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-list {
            list-style: none;
        }

        .filter-item {
            padding: 12px 15px;
            cursor: pointer;
            color: #495057;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 3px 0;
            font-weight: 500;
        }

        .filter-item:hover {
            background: #f8f9fa;
            color: #667eea;
            transform: translateX(5px);
        }

        .filter-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            transform: translateX(5px);
        }

        .filter-count {
            background: #e9ecef;
            color: #6c757d;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .filter-item.active .filter-count {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .products-main {
            flex: 1;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .view-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .view-toggle {
            display: flex;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .view-btn {
            padding: 10px 15px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .view-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .sort-select {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-weight: 500;
            min-width: 180px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            transition: all 0.3s ease;
        }

        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 250px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badges {
            position: absolute;
            top: 15px;
            left: 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .brand-badge {
            background: rgba(102, 126, 234, 0.9);
            color: white;
        }

        .type-badge {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .last-stock-badge {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .product-info {
            padding: 25px;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
            line-height: 1.3;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .product-pricing {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 1rem;
        }

        .sale-price {
            color: #dc3545;
            font-weight: 800;
            font-size: 1.4rem;
        }

        .size-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .size-tag {
            background: #f8f9fa;
            color: #495057;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #e9ecef;
        }

        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .no-results h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #495057;
            font-weight: 700;
        }

        .no-results p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            color: #6c757d;
        }

        /* WhatsApp Widget */
        .whatsapp-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .whatsapp-widget:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.6);
        }

        .whatsapp-widget::before {
            content: '💬';
            font-size: 28px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .nav-links {
                gap: 15px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .filter-row {
                flex-direction: column;
                gap: 20px;
            }
            
            .content-layout {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                position: static;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }
            
            .products-header {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .product-card:nth-child(even) {
            animation-delay: 0.1s;
        }

        .product-card:nth-child(3n) {
            animation-delay: 0.2s;
        }
        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        /* Product Detail Modal Styles */
        .product-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            backdrop-filter: blur(10px);
            overflow-y: auto;
        }

        .product-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 1200px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 25px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            color: #666;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .modal-close:hover {
            background: rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
        }

        .product-detail-image {
            position: relative;
        }

        .detail-image-main {
            width: 100%;
            height: 500px;
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .detail-image-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .last-stock-alert {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .product-detail-info {
            padding-top: 20px;
        }

        .product-detail-title {
            font-size: 2rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .size-selection-section {
            margin: 30px 0;
        }

        .size-selection-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .size-selection-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .free-pickup {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .size-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .size-option {
            aspect-ratio: 1;
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 600;
            color: #495057;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .size-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateY(-2px);
        }

        .size-option.selected {
            background: #2c3e50;
            color: white;
            border-color: #2c3e50;
            transform: translateY(-2px);
        }

        .size-option.unavailable {
            background: #f8f9fa;
            color: #adb5bd;
            border-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .size-option.unavailable:hover {
            transform: none;
            border-color: #e9ecef;
            background: #f8f9fa;
        }

        .stock-warning {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
            font-size: 0.95rem;
        }

        .product-detail-pricing {
            margin: 25px 0;
        }

        .detail-original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 1.1rem;
            margin-right: 15px;
        }

        .detail-sale-price {
            color: #2c3e50;
            font-weight: 800;
            font-size: 1.8rem;
        }

        .vat-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 30px 0;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            transition: all 0.2s ease;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-input {
            border: none;
            width: 60px;
            height: 40px;
            text-align: center;
            font-weight: 600;
            background: white;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .add-to-cart-btn {
            background: #2c3e50;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .add-to-cart-btn:hover:not(:disabled) {
            background: #1a252f;
            transform: translateY(-2px);
        }

        .add-to-cart-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .buy-now-btn {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .buy-now-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
        }

        .buy-now-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .discount-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 20px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .discount-section:hover {
            background: #fff0b3;
        }

        .discount-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .discount-title {
            font-weight: 600;
            color: #856404;
            font-size: 0.95rem;
        }

        .security-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .security-icon {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px;
            }

            .detail-image-main {
                height: 350px;
            }

            .product-detail-title {
                font-size: 1.5rem;
            }

            .size-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .modal-content {
                margin: 10px;
                max-height: 95vh;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-bar">
        <div class="nav-container">
            <div class="logo">Titanich Collection</div>
            <ul class="nav-links">
                <li><a href="#" class="nav-link active" data-page="homepage">Home</a></li>
                <li><a href="#" class="nav-link" data-page="products">All Products</a></li>
            </ul>
        </div>
    </nav>

    <div class="page-container">
        <!-- Homepage -->
        <div class="homepage active" id="homepage">
            <div class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">Premium Kicks Collection</h1>
                    <p class="hero-subtitle">Discover the finest selection of sneakers from top brands worldwide</p>
                </div>
            </div>

            <div class="quick-filters">
                <h2>Quick Filter</h2>
                <div class="filter-row">
                    <div class="filter-group">
                        <h3>🏷️ Brands</h3>
                        <div class="filter-buttons" id="homepage-brands">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    <div class="filter-group">
                        <h3>📏 Sizes</h3>
                        <div class="filter-buttons" id="homepage-sizes">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="featured-section">
                <div class="section-header">
                    <h2 class="section-title">Featured Products</h2>
                    <a href="#" class="view-all-btn" data-page="products">View All Products</a>
                </div>
                <div class="products-grid" id="homepage-products">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Products Page -->
        <div class="products-page" id="products-page">
            <div class="page-header">
                <h1 class="page-title">All Products</h1>
                <p class="results-info" id="results-counter">Loading products...</p>
            </div>

            <div class="content-layout">
                <div class="sidebar">
                    <div class="clear-section">
                        <div class="active-filters" id="active-filters">
                            <!-- Active filters will appear here -->
                        </div>
                        <button class="clear-all-btn" onclick="clearAllFilters()" style="display: none;" id="clear-all-btn">Clear All Filters</button>
                    </div>

                    <div class="filter-section">
                        <div class="stock-filter">
                            <input type="checkbox" id="instock" checked>
                            <label for="instock">In stock only</label>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3>💰 Price Range</h3>
                        <div class="price-section">
                            <div class="price-inputs">
                                <input type="number" class="price-input" id="min-price" value="0" min="0" max="20000" placeholder="Min">
                                <span>to</span>
                                <input type="number" class="price-input" id="max-price" value="20000" min="0" max="20000" placeholder="Max">
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3>🏷️ Brand</h3>
                        <ul class="filter-list" id="brand-filters">
                            <!-- Will be populated by JavaScript -->
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3>👟 Product Type</h3>
                        <ul class="filter-list" id="type-filters">
                            <!-- Will be populated by JavaScript -->
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3>📏 Size</h3>
                        <ul class="filter-list" id="size-filters">
                            <!-- Will be populated by JavaScript -->
                        </ul>
                    </div>
                </div>

                <div class="products-main">
                    <div class="products-header">
                        <div class="view-controls">
                            <div class="view-toggle">
                                <button class="view-btn active" data-view="grid">⚏ Grid</button>
                                <button class="view-btn" data-view="list">☰ List</button>
                            </div>
                        </div>
                        <select class="sort-select" id="sort-select">
                            <option value="featured">Featured</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="name-az">Name: A to Z</option>
                            <option value="name-za">Name: Z to A</option>
                            <option value="newest">Newest First</option>
                        </select>
                    </div>

                    <div class="products-grid" id="all-products">
                        <!-- Will be populated by JavaScript -->
                    </div>

                    <div class="no-results" id="no-results" style="display: none;">
                        <h3>No kicks found</h3>
                        <p>Try adjusting your filters to find what you're looking for</p>
                        <button class="clear-all-btn" onclick="clearAllFilters()">Reset Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Widget -->
    <a href="https://wa.me/254741421583" target="_blank" class="whatsapp-widget" title="Chat on WhatsApp">
    </a>

    <script>
        // Enhanced shoe data with more variety
        const shoes = [
            {
                id: 1,
                name: 'New Balance 327 Casablanca',
                image: 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400&h=300&fit=crop',
                original_price: 14000,
                sale_price: 5500,
                brand: 'New Balance',
                type: 'Running',
                sizes: ['UK 7', 'UK 8', 'UK 9', 'UK 10'],
                last_stock: true,
                in_stock: true,
                featured: true
            },
            {
                id: 2,
                name: 'Jordan 4 "Frozen Moments"',
                image: 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=400&h=300&fit=crop',
                original_price: 10400,
                sale_price: 5500,
                brand: 'Nike',
                type: 'Basketball',
                sizes: ['UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: true
            },
            {
                id: 3,
                name: 'Adidas Campus 00s',
                image: 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=300&fit=crop',
                original_price: 14300,
                sale_price: 5000,
                brand: 'Adidas',
                type: 'Lifestyle',
                sizes: ['UK 7', 'UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: true
            },
            {
                id: 4,
                name: 'Nike Dunk Low Triple Pink',
                image: 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=400&h=300&fit=crop',
                original_price: 11400,
                sale_price: 4500,
                brand: 'Nike',
                type: 'Lifestyle',
                sizes: ['UK 6', 'UK 7', 'UK 8', 'UK 9'],
                last_stock: true,
                in_stock: true,
                featured: true
            },
            {
                id: 5,
                name: 'Adidas Superstar Originals',
                image: 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&h=300&fit=crop',
                original_price: 13200,
                sale_price: 4000,
                brand: 'Adidas',
                type: 'Lifestyle',
                sizes: ['UK 8', 'UK 9', 'UK 10', 'UK 11', 'UK 12'],
                last_stock: true,
                in_stock: true,
                featured: false
            },
            {
                id: 6,
                name: 'New Balance 2002R Norway Spruce',
                image: 'https://images.unsplash.com/photo-1608667508764-6094824ac744?w=400&h=300&fit=crop',
                original_price: 15200,
                sale_price: 6000,
                brand: 'New Balance',
                type: 'Running',
                sizes: ['UK 7', 'UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 7,
                name: 'Adidas Samba OG Cream',
                image: 'https://images.unsplash.com/photo-1543508282-6319a3e2621f?w=400&h=300&fit=crop',
                original_price: 13500,
                sale_price: 5500,
                brand: 'Adidas',
                type: 'Lifestyle',
                sizes: ['UK 6', 'UK 7', 'UK 8', 'UK 9'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 8,
                name: 'New Balance 574 Legacy',
                image: 'https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?w=400&h=300&fit=crop',
                original_price: 12900,
                sale_price: 6000,
                brand: 'New Balance',
                type: 'Running',
                sizes: ['UK 8', 'UK 9', 'UK 10', 'UK 11', 'UK 12'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 9,
                name: 'Puma Suede XL Womens Black/Pink',
                image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop',
                original_price: 14300,
                sale_price: 6000,
                brand: 'Puma',
                type: 'Lifestyle',
                sizes: ['UK 5', 'UK 6', 'UK 7', 'UK 8', 'UK 9'],
                last_stock: true,
                in_stock: true,
                featured: false
            },
            {
                id: 10,
                name: 'Puma x Davido CA Pro',
                image: 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=400&h=300&fit=crop',
                original_price: 18200,
                sale_price: 18200,
                brand: 'Puma',
                type: 'Basketball',
                sizes: ['UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 11,
                name: 'Puma x Davido Leadcat Slides',
                image: 'https://images.unsplash.com/photo-1584735175315-9d5df23860e6?w=400&h=300&fit=crop',
                original_price: 6500,
                sale_price: 6500,
                brand: 'Puma',
                type: 'Slides',
                sizes: ['UK 6', 'UK 7', 'UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 12,
                name: 'Converse Chuck Taylor High',
                image: 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=300&fit=crop',
                original_price: 8500,
                sale_price: 7200,
                brand: 'Converse',
                type: 'Lifestyle',
                sizes: ['UK 6', 'UK 7', 'UK 8', 'UK 9', 'UK 10'],
                last_stock: false,
                in_stock: true,
                featured: false
            },
            {
                id: 13,
                name: 'Vans Old Skool Classic',
                image: 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=400&h=300&fit=crop',
                original_price: 9500,
                sale_price: 8000,
                brand: 'Vans',
                type: 'Lifestyle',
                sizes: ['UK 6', 'UK 7', 'UK 8', 'UK 9', 'UK 10'],
                last_stock: true,
                in_stock: true,
                featured: false
            },
            {
                id: 14,
                name: 'Nike Air Force 1 White',
                image: 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=300&fit=crop',
                original_price: 12000,
                sale_price: 10500,
                brand: 'Nike',
                type: 'Basketball',
                sizes: ['UK 7', 'UK 8', 'UK 9', 'UK 10', 'UK 11'],
                last_stock: false,
                in_stock: true,
                featured: true
            },
            {
                id: 15,
                name: 'Reebok Classic Leather',
                image: 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?w=400&h=300&fit=crop',
                original_price: 11000,
                sale_price: 9000,
                brand: 'Reebok',
                type: 'Lifestyle',
                sizes: ['UK 7', 'UK 8', 'UK 9', 'UK 10'],
                last_stock: false,
                in_stock: true,
                featured: false
            }
        ];

        // Application state
        let currentFilters = {
            brands: [],
            types: [],
            sizes: [],
            priceMin: 0,
            priceMax: 20000,
            inStock: true
        };

        let currentSort = 'featured';
        let currentView = 'grid';
        let currentPage = 'homepage';
        let filteredShoes = [...shoes];

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });

        function initializeApp() {
            setupNavigation();
            populateFilters();
            renderHomepage();
            renderProductsPage();
            setupEventListeners();
            updateResultsCounter();
        }

        // Navigation setup
        function setupNavigation() {
            const navLinks = document.querySelectorAll('.nav-link');
            const viewAllBtns = document.querySelectorAll('.view-all-btn');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    switchPage(page);
                });
            });

            viewAllBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    switchPage(page);
                });
            });
        }

        function switchPage(page) {
            // Update navigation
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.toggle('active', link.getAttribute('data-page') === page);
            });

            // Show/hide pages
            document.querySelectorAll('.homepage, .products-page').forEach(pageEl => {
                pageEl.classList.remove('active');
            });
            
            document.getElementById(page === 'homepage' ? 'homepage' : 'products-page').classList.add('active');
            
            currentPage = page;
            
            if (page === 'products') {
                applyFilters();
            }
        }

        // Populate filter options
        function populateFilters() {
            const brands = [...new Set(shoes.map(shoe => shoe.brand))].sort();
            const types = [...new Set(shoes.map(shoe => shoe.type))].sort();
            const allSizes = [...new Set(shoes.flatMap(shoe => shoe.sizes))].sort((a, b) => {
                const numA = parseInt(a.replace('UK ', ''));
                const numB = parseInt(b.replace('UK ', ''));
                return numA - numB;
            });

            // Homepage filters
            populateHomepageFilters(brands, allSizes);

            // Products page filters
            populateProductsPageFilters(brands, types, allSizes);
        }

        function populateHomepageFilters(brands, sizes) {
            const brandsContainer = document.getElementById('homepage-brands');
            const sizesContainer = document.getElementById('homepage-sizes');

            // Brand filters
            brands.forEach(brand => {
                const btn = document.createElement('button');
                btn.className = 'filter-btn';
                btn.textContent = brand;
                btn.setAttribute('data-brand', brand.toLowerCase());
                btn.addEventListener('click', () => toggleHomepageFilter('brand', brand.toLowerCase(), btn));
                brandsContainer.appendChild(btn);
            });

            // Size filters
            sizes.forEach(size => {
                const btn = document.createElement('button');
                btn.className = 'filter-btn';
                btn.textContent = size;
                btn.setAttribute('data-size', size.toLowerCase());
                btn.addEventListener('click', () => toggleHomepageFilter('size', size, btn));
                sizesContainer.appendChild(btn);
            });
        }

        function populateProductsPageFilters(brands, types, sizes) {
            const brandsList = document.getElementById('brand-filters');
            const typesList = document.getElementById('type-filters');
            const sizesList = document.getElementById('size-filters');

            // Brand filters
            brands.forEach(brand => {
                const count = shoes.filter(shoe => shoe.brand === brand).length;
                const li = createFilterItem(brand, count, 'brand');
                brandsList.appendChild(li);
            });

            // Type filters
            types.forEach(type => {
                const count = shoes.filter(shoe => shoe.type === type).length;
                const li = createFilterItem(type, count, 'type');
                typesList.appendChild(li);
            });

            // Size filters
            sizes.forEach(size => {
                const count = shoes.filter(shoe => shoe.sizes.includes(size)).length;
                const li = createFilterItem(size, count, 'size');
                sizesList.appendChild(li);
            });
        }

        function createFilterItem(value, count, filterType) {
            const li = document.createElement('li');
            li.className = 'filter-item';
            li.setAttribute('data-filter', filterType);
            li.setAttribute('data-value', value.toLowerCase());
            li.innerHTML = `
                <span>${value}</span>
                <span class="filter-count">${count}</span>
            `;
            
            li.addEventListener('click', function() {
                toggleProductsFilter(filterType, value.toLowerCase(), this);
            });
            
            return li;
        }

        // Homepage filter handling
        function toggleHomepageFilter(filterType, value, element) {
            element.classList.toggle('active');
            
            if (filterType === 'brand') {
                const index = currentFilters.brands.indexOf(value);
                if (index > -1) {
                    currentFilters.brands.splice(index, 1);
                } else {
                    currentFilters.brands.push(value);
                }
            } else if (filterType === 'size') {
                const index = currentFilters.sizes.indexOf(value);
                if (index > -1) {
                    currentFilters.sizes.splice(index, 1);
                } else {
                    currentFilters.sizes.push(value);
                }
            }
            
            renderHomepage();
        }

        // Products page filter handling
        function toggleProductsFilter(filterType, value, element) {
            const filterArray = currentFilters[filterType + 's'];
            const index = filterArray.indexOf(value);
            
            if (index > -1) {
                filterArray.splice(index, 1);
                element.classList.remove('active');
            } else {
                filterArray.push(value);
                element.classList.add('active');
            }
            
            applyFilters();
            updateActiveFiltersDisplay();
        }

        // Apply filters to products
        function applyFilters() {
            filteredShoes = shoes.filter(shoe => {
                // Brand filter
                if (currentFilters.brands.length > 0 && !currentFilters.brands.includes(shoe.brand.toLowerCase())) {
                    return false;
                }
                
                // Type filter
                if (currentFilters.types.length > 0 && !currentFilters.types.includes(shoe.type.toLowerCase())) {
                    return false;
                }
                
                // Size filter
                if (currentFilters.sizes.length > 0) {
                    const hasMatchingSize = shoe.sizes.some(size => 
                        currentFilters.sizes.includes(size.toLowerCase())
                    );
                    if (!hasMatchingSize) return false;
                }
                
                // Price filter
                if (shoe.sale_price < currentFilters.priceMin || shoe.sale_price > currentFilters.priceMax) {
                    return false;
                }
                
                // Stock filter
                if (currentFilters.inStock && !shoe.in_stock) {
                    return false;
                }
                
                return true;
            });
            
            sortProducts();
            if (currentPage === 'products') {
                renderProductsPage();
            } else {
                renderHomepage();
            }
            updateResultsCounter();
        }

        // Sort products
        function sortProducts() {
            filteredShoes.sort((a, b) => {
                switch (currentSort) {
                    case 'price-low':
                        return a.sale_price - b.sale_price;
                    case 'price-high':
                        return b.sale_price - a.sale_price;
                    case 'name-az':
                        return a.name.localeCompare(b.name);
                    case 'name-za':
                        return b.name.localeCompare(a.name);
                    case 'newest':
                        return b.id - a.id;
                    case 'featured':
                    default:
                        return (b.featured ? 1 : 0) - (a.featured ? 1 : 0) || b.id - a.id;
                }
            });
        }

        // Render homepage
        function renderHomepage() {
            const homepageProducts = document.getElementById('homepage-products');
            const productsToShow = currentFilters.brands.length > 0 || currentFilters.sizes.length > 0 
                ? filteredShoes.slice(0, 8) 
                : shoes.filter(shoe => shoe.featured).slice(0, 8);
            
            homepageProducts.innerHTML = productsToShow.map(shoe => createProductCard(shoe)).join('');
        }

        // Render products page
        function renderProductsPage() {
            const productsContainer = document.getElementById('all-products');
            const noResults = document.getElementById('no-results');
            
            if (filteredShoes.length === 0) {
                productsContainer.style.display = 'none';
                noResults.style.display = 'block';
                return;
            }
            
            productsContainer.style.display = 'grid';
            noResults.style.display = 'none';
            
            productsContainer.innerHTML = filteredShoes.map(shoe => createProductCard(shoe)).join('');
        }

        // Create product card HTML
        function createProductCard(shoe) {
            const hasDiscount = shoe.original_price !== shoe.sale_price;
            
            return `
                <div class="product-card" data-id="${shoe.id}">
                    <div class="product-image">
                        <img src="${shoe.image}" alt="${shoe.name}" loading="lazy">
                        <div class="product-badges">
                            <div class="badge brand-badge">${shoe.brand}</div>
                            <div class="badge type-badge">${shoe.type}</div>
                        </div>
                        ${shoe.last_stock ? '<div class="last-stock-badge">Last Stock!</div>' : ''}
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">${shoe.name}</h3>
                        <div class="product-meta">
                            <span>${shoe.brand}</span>
                            <span>${shoe.type}</span>
                        </div>
                        <div class="product-pricing">
                            ${hasDiscount ? `<span class="original-price">KSh${shoe.original_price.toLocaleString()}</span>` : ''}
                            <span class="sale-price">KSh${shoe.sale_price.toLocaleString()}</span>
                        </div>
                        <div class="size-options">
                            ${shoe.sizes.map(size => `<span class="size-tag">${size}</span>`).join('')}
                        </div>
                    </div>
                </div>
            `;
        }

        // Update active filters display
        function updateActiveFiltersDisplay() {
            const activeFiltersContainer = document.getElementById('active-filters');
            const clearAllBtn = document.getElementById('clear-all-btn');
            
            activeFiltersContainer.innerHTML = '';
            
            const allActiveFilters = [
                ...currentFilters.brands.map(brand => ({type: 'brands', value: brand, display: brand})),
                ...currentFilters.types.map(type => ({type: 'types', value: type, display: type})),
                ...currentFilters.sizes.map(size => ({type: 'sizes', value: size, display: size}))
            ];
            
            allActiveFilters.forEach(filter => {
                const tag = document.createElement('div');
                tag.className = 'filter-tag';
                tag.innerHTML = `
                    <span>${filter.display}</span>
                    <span class="remove" onclick="removeFilter('${filter.type}', '${filter.value}')">&times;</span>
                `;
                activeFiltersContainer.appendChild(tag);
            });
            
            clearAllBtn.style.display = allActiveFilters.length > 0 ? 'block' : 'none';
        }

        // Remove individual filter
        function removeFilter(filterType, value) {
            const filterArray = currentFilters[filterType];
            const index = filterArray.indexOf(value);
            
            if (index > -1) {
                filterArray.splice(index, 1);
                
                // Update UI
                const filterItem = document.querySelector(`[data-filter="${filterType.slice(0, -1)}"][data-value="${value}"]`);
                if (filterItem) {
                    filterItem.classList.remove('active');
                }
                
                applyFilters();
                updateActiveFiltersDisplay();
            }
        }

        // Clear all filters
        function clearAllFilters() {
            currentFilters = {
                brands: [],
                types: [],
                sizes: [],
                priceMin: 0,
                priceMax: 20000,
                inStock: true
            };
            
            // Reset UI
            document.querySelectorAll('.filter-item.active, .filter-btn.active').forEach(item => {
                item.classList.remove('active');
            });
            
            document.getElementById('min-price').value = 0;
            document.getElementById('max-price').value = 20000;
            document.getElementById('instock').checked = true;
            
            applyFilters();
            updateActiveFiltersDisplay();
        }

        // Update results counter
        function updateResultsCounter() {
            const counter = document.getElementById('results-counter');
            if (counter) {
                counter.textContent = `Showing ${filteredShoes.length} of ${shoes.length} products`;
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Sort dropdown
            const sortSelect = document.getElementById('sort-select');
            if (sortSelect) {
                sortSelect.addEventListener('change', function(e) {
                    currentSort = e.target.value;
                    sortProducts();
                    renderProductsPage();
                });
            }

            // View toggle
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    currentView = this.getAttribute('data-view');
                    const grid = document.getElementById('all-products');
                    
                    if (currentView === 'list') {
                        grid.classList.add('list-view');
                    } else {
                        grid.classList.remove('list-view');
                    }
                });
            });

            // Price inputs
            const minPrice = document.getElementById('min-price');
            const maxPrice = document.getElementById('max-price');
            
            if (minPrice) {
                minPrice.addEventListener('input', debounce(function(e) {
                    currentFilters.priceMin = parseInt(e.target.value) || 0;
                    applyFilters();
                }, 300));
            }

            if (maxPrice) {
                maxPrice.addEventListener('input', debounce(function(e) {
                    currentFilters.priceMax = parseInt(e.target.value) || 20000;
                    applyFilters();
                }, 300));
            }

            // Stock filter
            const stockFilter = document.getElementById('instock');
            if (stockFilter) {
                stockFilter.addEventListener('change', function(e) {
                    currentFilters.inStock = e.target.checked;
                    applyFilters();
                });
            }
        }

        // Utility function for debouncing
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Search functionality (bonus feature)
        function searchProducts(query) {
            if (!query.trim()) {
                applyFilters();
                return;
            }

            filteredShoes = shoes.filter(shoe => {
                const matchesSearch = shoe.name.toLowerCase().includes(query.toLowerCase()) ||
                                    shoe.brand.toLowerCase().includes(query.toLowerCase()) ||
                                    shoe.type.toLowerCase().includes(query.toLowerCase());
                
                if (!matchesSearch) return false;
                
                // Apply other filters
                if (currentFilters.brands.length > 0 && !currentFilters.brands.includes(shoe.brand.toLowerCase())) {
                    return false;
                }
                
                if (currentFilters.types.length > 0 && !currentFilters.types.includes(shoe.type.toLowerCase())) {
                    return false;
                }
                
                if (currentFilters.sizes.length > 0) {
                    const hasMatchingSize = shoe.sizes.some(size => 
                        currentFilters.sizes.includes(size.toLowerCase())
                    );
                    if (!hasMatchingSize) return false;
                }
                
                if (shoe.sale_price < currentFilters.priceMin || shoe.sale_price > currentFilters.priceMax) {
                    return false;
                }
                
                if (currentFilters.inStock && !shoe.in_stock) {
                    return false;
                }
                
                return true;
            });
            
            sortProducts();
            renderProductsPage();
            updateResultsCounter();
        }
    </script>