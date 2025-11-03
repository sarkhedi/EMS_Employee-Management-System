<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Dashboard - Real-Time EMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<style>
body {
font-family: 'Poppins', sans-serif;
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
min-height: 100vh;
color: white;
overflow-x: hidden;
}

/* Animated Background Particles */
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

/* Enhanced Glass Cards */
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
transform: translateY(-10px) scale(1.02);
box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2);
border-color: rgba(255, 255, 255, 0.5);
}

/* Enhanced Stat Cards */
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
position: relative;
z-index: 2;
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
position: relative;
z-index: 2;
transition: all 0.3s ease;
}

.stat-label {
font-size: 1rem;
font-weight: 600;
opacity: 0.9;
text-transform: uppercase;
letter-spacing: 1px;
position: relative;
z-index: 2;
}

/* Real-time Updates Animation */
.updating {
animation: dataUpdate 0.6s ease-in-out;
}

@keyframes dataUpdate {
0% { transform: scale(1); }
50% { transform: scale(1.1); color: #ffa500; }
100% { transform: scale(1); }
}

/* Enhanced Action Items */
.action-item {
background: rgba(255, 255, 255, 0.1);
border: 1px solid rgba(255, 255, 255, 0.2);
border-radius: 16px;
padding: 25px;
margin-bottom: 15px;
transition: all 0.4s ease;
cursor: pointer;
position: relative;
overflow: hidden;
text-decoration: none;
color: white;
}

.action-item::before {
content: '';
position: absolute;
left: 0;
top: 0;
bottom: 0;
width: 5px;
background: linear-gradient(135deg, #ff6b6b, #ffa500);
transform: scaleY(0);
transition: transform 0.3s ease;
}

.action-item::after {
content: '';
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
transform: translateX(-100%);
transition: transform 0.6s ease;
}

.action-item:hover {
transform: translateX(15px) scale(1.02);
background: rgba(255, 255, 255, 0.2);
border-color: rgba(255, 255, 255, 0.4);
box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
color: white;
text-decoration: none;
}

.action-item:hover::before {
transform: scaleY(1);
}

.action-item:hover::after {
transform: translateX(100%);
}

.action-icon {
width: 60px;
height: 60px;
border-radius: 15px;
display: flex;
align-items: center;
justify-content: center;
background: linear-gradient(135deg, #ff6b6b, #ffa500);
margin-right: 20px;
font-size: 1.5rem;
box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
}

/* Navigation Enhancements */
.navbar {
background: rgba(0, 0, 0, 0.15) !important;
backdrop-filter: blur(15px);
border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-link {
transition: all 0.3s ease;
margin: 0 5px;
border-radius: 10px;
}

.nav-link:hover, .nav-link.active {
background: rgba(255, 255, 255, 0.2) !important;
transform: translateY(-2px);
box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Real-time Status Indicators */
.status-indicator {
display: inline-block;
width: 12px;
height: 12px;
border-radius: 50%;
margin-right: 8px;
animation: pulse-status 2s infinite;
}

@keyframes pulse-status {
0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

.status-online { background: #28a745; }
.status-offline { background: #dc3545; }
.status-pending { background: #ffc107; }

/* Enhanced Buttons */
.modern-btn {
background: linear-gradient(135deg, #ff6b6b, #ffa500);
border: none;
border-radius: 15px;
padding: 15px 30px;
font-weight: 600;
color: white;
transition: all 0.3s ease;
text-decoration: none;
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
transform: translateY(-3px);
box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
color: white;
text-decoration: none;
}

/* FIXED NOTIFICATIONS - Proper positioning and container */
#notificationContainer {
position: fixed;
top: 20px;
right: 20px;
z-index: 10000;
width: 350px;
max-width: 90vw;
pointer-events: none;
}

.notification {
background: rgba(255, 255, 255, 0.95);
backdrop-filter: blur(20px);
border: 1px solid rgba(255, 255, 255, 0.3);
border-radius: 12px;
padding: 16px 20px;
margin-bottom: 10px;
color: #333;
box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
transform: translateX(400px);
opacity: 0;
transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
position: relative;
pointer-events: auto;
display: flex;
align-items: center;
min-height: 60px;
}

.notification.show {
transform: translateX(0);
opacity: 1;
}

.notification.success {
border-left: 4px solid #28a745;
background: rgba(40, 167, 69, 0.15);
backdrop-filter: blur(20px);
}

.notification.warning {
border-left: 4px solid #ffc107;
background: rgba(255, 193, 7, 0.15);
backdrop-filter: blur(20px);
}

.notification.error {
border-left: 4px solid #dc3545;
background: rgba(220, 53, 69, 0.15);
backdrop-filter: blur(20px);
}

.notification.info {
border-left: 4px solid #17a2b8;
background: rgba(23, 162, 184, 0.15);
backdrop-filter: blur(20px);
}

.notification i {
font-size: 1.2rem;
margin-right: 12px;
min-width: 20px;
}

.notification .notification-content {
flex-grow: 1;
font-weight: 500;
font-size: 0.9rem;
line-height: 1.4;
}

.notification .btn-close {
background: none;
border: none;
font-size: 1rem;
opacity: 0.6;
margin-left: 10px;
cursor: pointer;
color: inherit;
transition: opacity 0.2s ease;
}

.notification .btn-close:hover {
opacity: 1;
}

/* Notification Animation */
@keyframes slideInRight {
from {
transform: translateX(400px);
opacity: 0;
}
to {
transform: translateX(0);
opacity: 1;
}
}

@keyframes slideOutRight {
from {
transform: translateX(0);
opacity: 1;
}
to {
transform: translateX(400px);
opacity: 0;
}
}

/* Loading States */
.loading-spinner {
display: inline-block;
width: 20px;
height: 20px;
border: 3px solid rgba(255, 255, 255, 0.3);
border-radius: 50%;
border-top-color: white;
animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
to { transform: rotate(360deg); }
}

/* Small Weather Widget */
.weather-widget {
background: rgba(255, 255, 255, 0.05);
border-radius: 10px;
padding: 8px 15px;
text-align: center;
margin: 10px auto;
display: inline-block;
min-width: 120px;
border: 1px solid rgba(255, 255, 255, 0.1);
}

.weather-temp {
font-size: 1.2rem;
font-weight: 600;
margin-bottom: 2px;
}

.weather-icon {
font-size: 1rem !important;
margin-bottom: 5px !important;
}

.weather-widget small {
font-size: 0.75rem;
opacity: 0.8;
}

/* Progress Bars */
.progress-bar-animated {
animation: progress-animation 2s ease-in-out;
}

@keyframes progress-animation {
from { width: 0; }
to { width: var(--progress-width); }
}

/* Responsive Enhancements */
@media (max-width: 768px) {
.glass-card {
padding: 20px;
margin-bottom: 20px;
}
.stat-card {
height: 160px;
padding: 20px 15px;
}
.stat-icon {
font-size: 2.2rem;
}
.stat-number {
font-size: 2rem;
}
.action-item {
padding: 20px;
}
.weather-widget {
min-width: 100px;
padding: 6px 12px;
}
.weather-temp {
font-size: 1rem;
}

/* Mobile Notification Adjustments */
#notificationContainer {
width: 300px;
right: 10px;
top: 10px;
}

.notification {
padding: 12px 16px;
font-size: 0.85rem;
}
}

/* Custom Scrollbar */
::-webkit-scrollbar {
width: 8px;
}

::-webkit-scrollbar-track {
background: rgba(255, 255, 255, 0.1);
border-radius: 4px;
}

::-webkit-scrollbar-thumb {
background: rgba(255, 255, 255, 0.3);
border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
background: rgba(255, 255, 255, 0.5);
}

/* Manual Refresh Button */
.refresh-btn {
position: fixed;
bottom: 30px;
right: 30px;
width: 60px;
height: 60px;
border-radius: 50%;
background: linear-gradient(135deg, #ff6b6b, #ffa500);
border: none;
color: white;
font-size: 1.5rem;
box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
transition: all 0.3s ease;
z-index: 1000;
cursor: pointer;
}

.refresh-btn:hover {
transform: scale(1.1) rotate(180deg);
box-shadow: 0 15px 35px rgba(255, 107, 107, 0.5);
}
</style>
<body>
<!-- Animated Particles Background -->
<div class="particles" id="particles"></div>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top">
<div class="container">
<a class="navbar-brand text-white fw-bold" href="dashboard.php">
<i class="fas fa-user-circle me-2"></i>EMS 

</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<i class="fas fa-bars text-white"></i>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<div class="navbar-nav ms-auto">
<a class="nav-link text-white active" href="dashboard.php">
<i class="fas fa-tachometer-alt me-1"></i>Dashboard
</a>
<a class="nav-link text-white" href="profile.php">
<i class="fas fa-user me-1"></i>Profile
</a>
<a class="nav-link text-white" href="attendance.php">
<i class="fas fa-calendar-check me-1"></i>Attendance
</a>
<a class="nav-link text-white" href="leave_request.php">
<i class="fas fa-plane me-1"></i>Leave
</a>
<a class="nav-link text-white" href="my_project.php">
<i class="fas fa-project-diagram me-1"></i>Projects
</a>
<a class="nav-link text-white" href="salary.php">
<i class="fas fa-money-bill-wave me-1"></i>Salary
</a>
<!-- <span class="nav-link text-white">
<i class="fas fa-user-circle me-1"></i>Sarkhedi Om
</span> -->
<a class="nav-link text-white" href="../logout.php">
<i class="fas fa-sign-out-alt me-1"></i>Logout
</a>
</div>
</div>
</div>
</nav>

<div class="container" style="margin-top: 100px;">
<!-- Welcome Header with Small Weather Widget -->
<div class="glass-card text-center">
<h1 class="mb-3">
<i class="fas fa-tachometer-alt me-3"></i>
Welcome, Employee!
</h1>
<p class="mb-3">Employee Dashboard • <span id="currentDateTime">Friday, August 29, 2025 - 5:48 PM IST</span></p>
<!-- Small Weather Widget -->
<div class="weather-widget">
<i class="fas fa-sun text-warning weather-icon"></i>
<div class="weather-temp">28°C</div>
<small>Partly Cloudy</small>
</div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
<div class="col-lg-4 col-md-6 mb-4">
<div class="stat-card" data-stat="present">
<div class="stat-icon">
<i class="fas fa-calendar-check"></i>
</div>
<div class="stat-number" id="presentDays">
<span class="loading-spinner"></span>
</div>
<div class="stat-label">Present Days</div>
<div class="progress mt-2">
<div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="presentProgress"></div>
</div>
</div>
</div>
<div class="col-lg-4 col-md-6 mb-4">
<div class="stat-card" data-stat="leaves">
<div class="stat-icon">
<i class="fas fa-plane"></i>
</div>
<div class="stat-number" id="pendingLeaves">
<span class="loading-spinner"></span>
</div>
<div class="stat-label">Pending Leaves</div>
<div class="progress mt-2">
<div class="progress-bar bg-warning" role="progressbar" style="width: 0%" id="leaveProgress"></div>
</div>
</div>
</div>
<div class="col-lg-4 col-md-6 mb-4">
<div class="stat-card" data-stat="projects">
<div class="stat-icon">
<i class="fas fa-project-diagram"></i>
</div>
<div class="stat-number" id="myProjects">
<span class="loading-spinner"></span>
</div>
<div class="stat-label">My Projects</div>
<div class="progress mt-2">
<div class="progress-bar bg-info" role="progressbar" style="width: 0%" id="projectProgress"></div>
</div>
</div>
</div>
</div>

<div class="row">
<!-- Quick Actions Section -->
<div class="col-lg-8">
<div class="glass-card">
<h3><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h3>
<div class="row">
<div class="col-md-6 mb-3">
<a href="profile.php" class="action-item d-block">
<div class="d-flex align-items-center">
<div class="action-icon">
<i class="fas fa-user"></i>
</div>
<div>
<h6 class="mb-1">👤 My Profile</h6>
<small class="text-muted">Update personal information</small>
</div>
</div>
</a>
</div>
<div class="col-md-6 mb-3">
<a href="attendance.php" class="action-item d-block">
<div class="d-flex align-items-center">
<div class="action-icon">
<i class="fas fa-calendar-check"></i>
</div>
<div>
<h6 class="mb-1">🗓 Attendance</h6>
<small class="text-muted">View attendance history</small>
</div>
</div>
</a>
</div>
<div class="col-md-6 mb-3">
<a href="leave_request.php" class="action-item d-block">
<div class="d-flex align-items-center">
<div class="action-icon">
<i class="fas fa-plane"></i>
</div>
<div>
<h6 class="mb-1">📝 Apply Leave</h6>
<small class="text-muted">Submit leave requests</small>
</div>
</div>
</a>
</div>
<div class="col-md-6 mb-3">
<a href="salary.php" class="action-item d-block">
<div class="d-flex align-items-center">
<div class="action-icon">
<i class="fas fa-money-bill-wave"></i>
</div>
<div>
<h6 class="mb-1">💰 Salary</h6>
<small class="text-muted">View salary details</small>
</div>
</div>
</a>
</div>
<div class="col-md-12 mb-3">
<a href="my_project.php" class="action-item d-block">
<div class="d-flex align-items-center">
<div class="action-icon">
<i class="fas fa-project-diagram"></i>
</div>
<div>
<h6 class="mb-1">📊 My Projects</h6>
<small class="text-muted">View and manage assigned projects</small>
</div>
</div>
</a>
</div>
</div>
</div>
</div>

<!-- Sidebar with Recent Activity and Employee Info -->
<div class="col-lg-4">
<!-- Recent Activity -->
<div class="glass-card">
<h3><i class="fas fa-history text-info me-2"></i>Recent Activity</h3>
<div class="text-center py-4" id="recentActivity">
<i class="fas fa-bell fa-3x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
<p style="color: rgba(255, 255, 255, 0.6);">Loading recent activity...</p>
<small style="color: rgba(255, 255, 255, 0.5);">Please wait</small>
</div>
</div>

<!-- Employee Info -->
<div class="glass-card">
<h3><i class="fas fa-id-card text-success me-2"></i>My Info</h3>
<div class="employee-info">
<div class="d-flex justify-content-between align-items-center mb-2">
<strong>Name:</strong>
<span>Sarkhedi Om</span>
</div>
<div class="d-flex justify-content-between align-items-center mb-2">
<strong>Employee ID:</strong>
<span>EMP-0023</span>
</div>
<div class="d-flex justify-content-between align-items-center mb-2">
<strong>Department:</strong>
<span>HR</span>
</div>
<div class="d-flex justify-content-between align-items-center mb-2">
<strong>Position:</strong>
<span>Manager</span>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
<strong>Join Date:</strong>
<span>Aug 23, 2025</span>
</div>
</div>
<a href="profile.php" class="modern-btn w-100">
<i class="fas fa-edit me-2"></i>Edit Profile
</a>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Enhanced Real-time Dashboard System with Fixed Notifications
class RealTimeDashboard {
    constructor() {
        this.employeeId = 'EMP-0023';
        this.lastUpdateTime = null;
        this.updateInterval = 30000; // 30 seconds
        this.isConnected = true;
        this.retryAttempts = 0;
        this.maxRetryAttempts = 3;
        this.notificationQueue = [];
        
        this.init();
    }

    init() {
        this.createParticles();
        this.loadInitialData();
        this.startRealTimeUpdates();
        this.initEventListeners();
        this.updateDateTime();
        this.checkConnection();
        
        // Test notification on load
        setTimeout(() => {
            this.showNotification('Dashboard initialized successfully! Real-time updates are active.', 'success', 4000);
        }, 2000);
    }

    // Create animated particles
    createParticles() {
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
            particlesContainer.appendChild(particle);
        }
    }

    // Load initial data from server
    async loadInitialData() {
        try {
            await this.fetchDashboardData();
            this.showNotification('Data loaded successfully', 'success');
        } catch (error) {
            console.error('Failed to load initial data:', error);
            this.showNotification('Using offline data - connection issue detected', 'warning');
            this.setPlaceholderData();
        }
    }

    // Fetch real data from server
    async fetchDashboardData() {
        try {
            // Simulate API call for demo purposes
            // Replace this with actual fetch to your PHP endpoint
            const simulatedData = await this.simulateAPICall();
            
            if (simulatedData.success) {
                this.updateDashboardStats(simulatedData);
                this.updateRecentActivity(simulatedData.recent_activity || []);
                this.lastUpdateTime = new Date();
                this.isConnected = true;
                this.retryAttempts = 0;
                
                // Show update notification every 5th update
                if (Math.random() > 0.8) {
                    this.showNotification('Data refreshed automatically', 'info', 2000);
                }
            } else {
                throw new Error(simulatedData.message || 'Failed to fetch data');
            }

        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            this.handleConnectionError();
            throw error;
        }
    }

    // Simulate API call (replace with real fetch)
    async simulateAPICall() {
        return new Promise((resolve) => {
            setTimeout(() => {
                const presentDays = Math.floor(Math.random() * 5) + 20;
                const pendingLeaves = Math.floor(Math.random() * 3) + 1;
                const myProjects = Math.floor(Math.random() * 3) + 3;
                
                resolve({
                    success: true,
                    present_days: presentDays,
                    total_working_days: 30,
                    pending_leaves: pendingLeaves,
                    total_leave_requests: 10,
                    my_projects: myProjects,
                    total_projects: 15,
                    recent_activity: [
                        { 
                            type: 'attendance', 
                            message: 'Attendance marked for today', 
                            time: new Date().toISOString() 
                        },
                        { 
                            type: 'leave', 
                            message: 'Leave request submitted', 
                            time: new Date(Date.now() - 3600000).toISOString() 
                        },
                        { 
                            type: 'project', 
                            message: 'Project task updated', 
                            time: new Date(Date.now() - 7200000).toISOString() 
                        }
                    ]
                });
            }, 1000);
        });
    }

    // Update dashboard statistics
    updateDashboardStats(data) {
        if (data.present_days !== undefined) {
            this.animateStatUpdate('presentDays', data.present_days, data.total_working_days || 30);
        }

        if (data.pending_leaves !== undefined) {
            this.animateStatUpdate('pendingLeaves', data.pending_leaves, data.total_leave_requests || 10);
        }

        if (data.my_projects !== undefined) {
            this.animateStatUpdate('myProjects', data.my_projects, data.total_projects || 15);
        }
    }

    // Set placeholder data when server is unavailable
    setPlaceholderData() {
        this.animateStatUpdate('presentDays', 22, 30);
        this.animateStatUpdate('pendingLeaves', 2, 10);
        this.animateStatUpdate('myProjects', 5, 15);
        
        const placeholderActivity = [
            { message: 'Dashboard loaded in offline mode', time: new Date(), type: 'system' }
        ];
        this.updateRecentActivity(placeholderActivity);
    }

    // Animate stat updates with loading states
    animateStatUpdate(statId, newValue, maxValue = 100) {
        const element = document.getElementById(statId);
        const currentValue = parseInt(element.textContent) || 0;
        
        // Show loading if first time
        if (element.querySelector('.loading-spinner')) {
            element.innerHTML = '';
        }
        
        // Animate number change
        element.classList.add('updating');
        
        // Animate counter
        this.animateCounter(element, currentValue, newValue, 1000);
        
        setTimeout(() => {
            element.classList.remove('updating');
            this.updateProgressBar(statId, newValue, maxValue);
        }, 1000);
    }

    // Animate counter from old to new value
    animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        const difference = end - start;

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (difference * this.easeOutQuart(progress)));
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                element.textContent = end;
            }
        };
        
        requestAnimationFrame(step);
    }

    // Easing function for smooth animation
    easeOutQuart(t) {
        return 1 - (--t) * t * t * t;
    }

    // Update progress bars
    updateProgressBar(statType, value, maxValue) {
        const progressBars = {
            presentDays: 'presentProgress',
            pendingLeaves: 'leaveProgress',
            myProjects: 'projectProgress'
        };

        if (progressBars[statType]) {
            const progressElement = document.getElementById(progressBars[statType]);
            const percentage = Math.min((value / maxValue) * 100, 100);
            
            progressElement.style.setProperty('--progress-width', percentage + '%');
            progressElement.style.width = percentage + '%';
            progressElement.classList.add('progress-bar-animated');
            
            setTimeout(() => {
                progressElement.classList.remove('progress-bar-animated');
            }, 2000);
        }
    }

    // Update recent activity section
    updateRecentActivity(activities) {
        const recentActivity = document.getElementById('recentActivity');
        
        if (!activities || activities.length === 0) {
            recentActivity.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-bell fa-3x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
                    <p style="color: rgba(255, 255, 255, 0.6);">No recent activity</p>
                    <small style="color: rgba(255, 255, 255, 0.5);">Recent updates will appear here</small>
                </div>
            `;
            return;
        }

        const activityHtml = activities.slice(0, 3).map(activity => {
            const time = new Date(activity.time || activity.created_at);
            const timeStr = time.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: 'numeric', 
                minute: '2-digit' 
            });
            
            const iconMap = {
                leave: 'fa-plane',
                attendance: 'fa-calendar-check',
                project: 'fa-project-diagram',
                system: 'fa-cog',
                default: 'fa-bell'
            };
            
            const icon = iconMap[activity.type] || iconMap.default;
            
            return `
                <div class="activity-item mb-3 p-2 rounded" style="background: rgba(255, 255, 255, 0.05);">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas ${icon} me-2 text-info"></i>
                        <small class="text-muted">${timeStr}</small>
                    </div>
                    <p class="mb-0 small" style="color: rgba(255, 255, 255, 0.8);">
                        ${activity.message}
                    </p>
                </div>
            `;
        }).join('');

        recentActivity.innerHTML = `<div class="text-start">${activityHtml}</div>`;
    }

    // Start real-time update system
    startRealTimeUpdates() {
        // Update date/time every second
        setInterval(() => {
            this.updateDateTime();
        }, 1000);

        // Update weather every 5 minutes
        setInterval(() => {
            this.updateWeather();
        }, 300000);

        // Fetch dashboard data every 30 seconds
        setInterval(async () => {
            if (this.isConnected) {
                try {
                    await this.fetchDashboardData();
                } catch (error) {
                    // Error handling is done in fetchDashboardData
                }
            }
        }, this.updateInterval);

        // Check connection every minute
        setInterval(() => {
            this.checkConnection();
        }, 60000);
    }

    // Check server connection
    async checkConnection() {
        try {
            // Simulate connection check
            const isOnline = navigator.onLine;
            
            if (isOnline) {
                if (!this.isConnected) {
                    this.isConnected = true;
                    this.retryAttempts = 0;
                    this.showNotification('Connection restored! Resuming real-time updates.', 'success');
                    await this.fetchDashboardData();
                }
                this.updateConnectionStatus(true);
            } else {
                throw new Error('No internet connection');
            }
        } catch (error) {
            this.handleConnectionError();
        }
    }

    // Handle connection errors
    handleConnectionError() {
        this.isConnected = false;
        this.retryAttempts++;
        this.updateConnectionStatus(false);

        if (this.retryAttempts <= this.maxRetryAttempts) {
            this.showNotification(`Connection issue detected. Retrying... (${this.retryAttempts}/${this.maxRetryAttempts})`, 'warning');
        } else {
            this.showNotification('Connection lost. Dashboard will work with cached data.', 'error');
        }
    }

    // Update connection status indicator
    updateConnectionStatus(isConnected) {
        const statusIndicator = document.querySelector('.status-indicator');
        if (statusIndicator) {
            statusIndicator.className = `status-indicator ${isConnected ? 'status-online' : 'status-offline'}`;
        }
    }

    // Update date and time
    updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZoneName: 'short'
        };
        document.getElementById('currentDateTime').textContent = 
            now.toLocaleDateString('en-US', options);
    }

    // Update weather information
    updateWeather() {
        const temperatures = [25, 26, 27, 28, 29, 30];
        const conditions = ['Sunny', 'Partly Cloudy', 'Cloudy', 'Clear'];
        const icons = ['fa-sun', 'fa-cloud-sun', 'fa-cloud', 'fa-moon'];
        
        const randomTemp = temperatures[Math.floor(Math.random() * temperatures.length)];
        const randomCondition = conditions[Math.floor(Math.random() * conditions.length)];
        const randomIcon = icons[Math.floor(Math.random() * icons.length)];
        
        const weatherWidget = document.querySelector('.weather-widget');
        if (weatherWidget) {
            weatherWidget.querySelector('.weather-temp').textContent = randomTemp + '°C';
            weatherWidget.querySelector('small').textContent = randomCondition;
            
            const iconElement = weatherWidget.querySelector('i');
            iconElement.className = `fas ${randomIcon} text-warning weather-icon`;
        }
    }

    // Initialize event listeners
    initEventListeners() {
        // Enhanced hover effects
        document.querySelectorAll('.stat-card').forEach((card) => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-15px) rotateY(5deg) scale(1.05)';
                card.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) rotateY(0) scale(1)';
                card.style.zIndex = '1';
            });
        });

        // Action item interactions
        document.querySelectorAll('.action-item').forEach(item => {
            item.addEventListener('click', (e) => {
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.transform = 'translateX(15px) scale(1.02)';
                }, 150);
            });
        });

        // Manual refresh button
        const refreshButton = document.getElementById('refreshButton');
        if (refreshButton) {
            refreshButton.addEventListener('click', async () => {
                this.showNotification('Refreshing dashboard manually...', 'info');
                try {
                    await this.fetchDashboardData();
                    this.showNotification('Dashboard refreshed successfully!', 'success');
                } catch (error) {
                    this.showNotification('Failed to refresh dashboard', 'error');
                }
            });
        }

        // Test notification buttons (you can add these for testing)
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey) {
                switch (e.key) {
                    case 'S':
                        this.showNotification('Success notification test', 'success');
                        break;
                    case 'W':
                        this.showNotification('Warning notification test', 'warning');
                        break;
                    case 'E':
                        this.showNotification('Error notification test', 'error');
                        break;
                    case 'I':
                        this.showNotification('Info notification test', 'info');
                        break;
                }
            }
        });
    }

    // FIXED Show notifications with proper positioning and animation
    showNotification(message, type = 'success', duration = 4000) {
        const notificationContainer = document.getElementById('notificationContainer');
        
        if (!notificationContainer) {
            console.error('Notification container not found');
            return;
        }

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            warning: 'fa-exclamation-triangle',
            error: 'fa-times-circle',
            info: 'fa-info-circle'
        };

        const colors = {
            success: '#28a745',
            warning: '#ffc107',
            error: '#dc3545',
            info: '#17a2b8'
        };
        
        notification.innerHTML = `
            <i class="fas ${icons[type] || icons.info}" style="color: ${colors[type]}"></i>
            <div class="notification-content">${message}</div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add notification to container
        notificationContainer.appendChild(notification);
        
        // Force reflow to ensure transition works
        notification.offsetHeight;
        
        // Show notification with animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 50);
        
        // Auto-remove notification after duration
        setTimeout(() => {
            this.removeNotification(notification);
        }, duration);

        // Add click handler for manual close
        const closeBtn = notification.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeNotification(notification);
            });
        }

        // Limit number of notifications
        const notifications = notificationContainer.querySelectorAll('.notification');
        if (notifications.length > 5) {
            this.removeNotification(notifications[0]);
        }

        console.log(`Notification shown: ${type} - ${message}`);
    }

    // Remove notification with animation
    removeNotification(notification) {
        if (notification && notification.parentElement) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 400);
        }
    }

    // Force refresh method
    async forceRefresh() {
        this.showNotification('Force refreshing all dashboard data...', 'info');
        try {
            await this.fetchDashboardData();
            this.showNotification('All data refreshed successfully!', 'success');
        } catch (error) {
            this.showNotification('Failed to refresh data. Check your connection.', 'error');
        }
    }
}

// Initialize Dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new RealTimeDashboard();
    
    // Test notifications after 3 seconds
    setTimeout(() => {
        console.log('Testing notification system...');
    }, 3000);
});

// Global function to manually refresh
function refreshDashboard() {
    if (window.dashboard) {
        window.dashboard.forceRefresh();
    }
}

// Testing functions (can be called from console)
function testNotifications() {
    if (window.dashboard) {
        window.dashboard.showNotification('Testing success notification', 'success');
        setTimeout(() => window.dashboard.showNotification('Testing warning notification', 'warning'), 1000);
        setTimeout(() => window.dashboard.showNotification('Testing error notification', 'error'), 2000);
        setTimeout(() => window.dashboard.showNotification('Testing info notification', 'info'), 3000);
    }
}
</script>
</body>
</html>
