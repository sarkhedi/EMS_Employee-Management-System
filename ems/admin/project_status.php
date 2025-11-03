<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Status - EMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            color: white;
        }

        /* Animated background particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Main container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Glass morphism header */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            color: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            border-radius: 22px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .glass-card:hover::before {
            opacity: 1;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.15);
        }

        .header-title {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 12px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
            transition: all 0.3s ease;
            margin: 0 auto;
            display: flex;
            width: fit-content;
        }

        .admin-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(255, 107, 107, 0.4);
        }

        .section-title {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            border-radius: 2px;
        }

        /* Enhanced stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-15px) rotateY(5deg) scale(1.05);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse-icon 3s infinite;
        }

        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(45deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .stat-label {
            font-size: 1rem;
            font-weight: 600;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Modern table container */
        .table-wrapper {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
        }

        .table-search {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 10px 15px;
            color: white;
            font-size: 14px;
            width: 250px;
        }

        .table-search::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 900px;
        }

        .modern-table thead {
            background: rgba(255, 255, 255, 0.15);
        }

        .modern-table th {
            padding: 20px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
        }

        .modern-table th:first-child {
            border-top-left-radius: 15px;
        }

        .modern-table th:last-child {
            border-top-right-radius: 15px;
        }

        .modern-table td {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .modern-table tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .modern-table tr:hover td {
            color: #ffffff;
        }

        /* Project name styling */
        .project-name {
            font-weight: 700;
            font-size: 16px;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .project-id {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Employee info styling */
        .employee-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .employee-details h4 {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .employee-dept {
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Status badges - using your theme colors */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-assigned {
            background: rgba(0, 123, 255, 0.8);
            color: white;
            border: 1px solid rgba(0, 123, 255, 0.3);
        }

        .status-in-progress {
            background: rgba(255, 193, 7, 0.8);
            color: #fff3cd;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.8);
            color: #d4edda;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-on-hold {
            background: rgba(220, 53, 69, 0.8);
            color: #f8d7da;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* Timeline styling */
        .timeline-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .timeline-icon {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
        }

        .timeline-start {
            background: rgba(40, 167, 69, 0.8);
            color: white;
        }

        .timeline-end {
            background: rgba(220, 53, 69, 0.8);
            color: white;
        }

        /* Description styling */
        .project-description {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            line-height: 1.4;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .modern-btn:hover::before {
            left: 100%;
        }

        .modern-btn:hover {
            text-decoration: none;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            .main-container {
                padding: 15px;
            }

            .glass-card {
                padding: 25px;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .table-search {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-card {
                padding: 20px;
                height: 160px;
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .stat-icon {
                font-size: 2.2rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 15px 12px;
                font-size: 13px;
            }

            .employee-card {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .modern-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .modern-table {
                min-width: 700px;
            }
        }

        /* Animation */
        .main-container {
            animation: fadeInUp 0.8s ease-out;
        }

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

        .stat-card {
            animation: slideInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-table tr {
            animation: fadeInLeft 0.5s ease-out;
            animation-fill-mode: both;
        }

        .modern-table tr:nth-child(1) { animation-delay: 0.1s; }
        .modern-table tr:nth-child(2) { animation-delay: 0.2s; }
        .modern-table tr:nth-child(3) { animation-delay: 0.3s; }
        .modern-table tr:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Custom scrollbar */
        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <!-- Animated Particles Background -->
    <div class="particles" id="particles"></div>
    
    <div class="main-container">
        <!-- Modern Header -->
        <div class="glass-card">
            <h1 class="header-title">
                <i class="fas fa-project-diagram me-3"></i>
                Project Control Center
            </h1>
            <p class="header-subtitle">Advanced project monitoring and management dashboard</p>
            <div class="admin-badge">
                <i class="fas fa-user-shield"></i>
                Welcome, Admin
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="glass-card">
            <h2 class="section-title">Projects Overview</h2>
            
            <!-- Enhanced Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="stat-number">4</div>
                    <div class="stat-label">Total Projects</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">3</div>
                    <div class="stat-label">Active Employees</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">2</div>
                    <div class="stat-label">Departments</div>
                </div>
            </div>

            <!-- Modern Table -->
            <div class="table-wrapper">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="fas fa-list-ul me-2"></i>
                        Project Assignments
                    </h3>
                    <input type="text" class="table-search" placeholder="Search projects..." id="searchInput">
                </div>
                
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-project-diagram me-2"></i>Project</th>
                                <th><i class="fas fa-user me-2"></i>Employee</th>
                                <th><i class="fas fa-flag me-2"></i>Status</th>
                                <th><i class="fas fa-calendar me-2"></i>Timeline</th>
                                <th><i class="fas fa-info-circle me-2"></i>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="project-name">Database Design</div>
                                    <div class="project-id">#PRJ-001</div>
                                </td>
                                <td>
                                    <div class="employee-card">
                                        <div class="employee-avatar">SO</div>
                                        <div class="employee-details">
                                            <h4>Sarkhedi Om</h4>
                                            <div class="employee-dept">HR</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-assigned">
                                        <i class="fas fa-tasks"></i>
                                        Assigned
                                    </span>
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-start">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span>Start: Aug 28, 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-end">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <span>End: Sep 12, 2025</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="project-description" title="Database architecture and design implementation">
                                        Database architecture and design implementation
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="project-name">E-commerce Platform</div>
                                    <div class="project-id">#PRJ-002</div>
                                </td>
                                <td>
                                    <div class="employee-card">
                                        <div class="employee-avatar">JS</div>
                                        <div class="employee-details">
                                            <h4>Jenis Sanandiya</h4>
                                            <div class="employee-dept">IT</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-in-progress">
                                        <i class="fas fa-spinner"></i>
                                        In Progress
                                    </span>
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-start">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span>Start: Aug 26, 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-end">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <span>End: Sep 02, 2025</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="project-description" title="Clothing Brand E-commerce Platform Development">
                                        Clothing Brand E-commerce Platform Development
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="project-name">Inventory Management System</div>
                                    <div class="project-id">#PRJ-003</div>
                                </td>
                                <td>
                                    <div class="employee-card">
                                        <div class="employee-avatar">JS</div>
                                        <div class="employee-details">
                                            <h4>Jenis Sanandiya</h4>
                                            <div class="employee-dept">IT</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-assigned">
                                        <i class="fas fa-tasks"></i>
                                        Assigned
                                    </span>
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-start">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span>Start: Aug 26, 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-end">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <span>End: Aug 31, 2025</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="project-description" title="IoT App for Smart Homes Inventory Management">
                                        IoT App for Smart Homes Inventory Management
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="project-name">Mobile App Development</div>
                                    <div class="project-id">#PRJ-004</div>
                                </td>
                                <td>
                                    <div class="employee-card">
                                        <div class="employee-avatar">SM</div>
                                        <div class="employee-details">
                                            <h4>Sarkhedi Mahek</h4>
                                            <div class="employee-dept">HR</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-completed">
                                        <i class="fas fa-check"></i>
                                        Completed
                                    </span>
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-start">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span>Start: Aug 25, 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon timeline-end">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <span>End: Sep 26, 2025</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="project-description" title="iOS Mobile Application Development">
                                        iOS Mobile Application Development
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="assign_project.php" class="modern-btn">
                    <i class="fas fa-plus"></i>
                    Assign New Project
                </a>
                <a href="dashboard.php" class="modern-btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        // Create animated particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('.modern-table tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        row.style.animation = 'fadeInLeft 0.3s ease-out';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Enhanced table interactions
        function initializeTableInteractions() {
            const tableRows = document.querySelectorAll('.modern-table tbody tr');
            
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    // Add click feedback
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });

                // Enhanced hover effects
                row.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 8px 25px rgba(255, 255, 255, 0.1)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.boxShadow = '';
                });
            });
        }

        // Status badge interactions
        function initializeStatusBadges() {
            const statusBadges = document.querySelectorAll('.status-badge');
            
            statusBadges.forEach(badge => {
                badge.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = '50%';
                    ripple.style.top = '50%';
                    ripple.style.marginLeft = '-10px';
                    ripple.style.marginTop = '-10px';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    
                    this.style.position = 'relative';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            initializeSearch();
            initializeTableInteractions();
            initializeStatusBadges();

            // Add ripple animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            console.log('🚀 EMS Project Status Dashboard initialized successfully!');
        });
    </script>
</body>
</html>
