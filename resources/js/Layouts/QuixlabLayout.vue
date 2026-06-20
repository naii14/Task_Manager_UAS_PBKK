<template>
    <div id="main-wrapper">

        <!-- Header -->
        <div class="header">
            <div class="header-content clearfix">
                <h3>Task Manager</h3>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="nk-sidebar">
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label">Menu</li>

                    <li>
                        <a href="/dashboard">
                            <i class="icon-speedometer menu-icon"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="/tasks">
                            <i class="icon-note menu-icon"></i>
                            <span class="nav-text">Tugas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/calendar">
                            <i class="icon-calendar menu-icon"></i>
                            <span class="nav-text">Kalender</span>
                        </a>
                    </li>

                    <li>
                        <a href="/profile">
                            <i class="icon-user menu-icon"></i>
                            <span class="nav-text">Profil</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Content -->
        <div class="content-body">
            <div class="container-fluid mt-3">
                <slot />
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="copyright">
                <p>Task Manager</p>
            </div>
        </div>

    </div>
</template>
