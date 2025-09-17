project-management-dashboard/
├── docker-compose.yml
├── Dockerfile
├── README.md
├── .env.example
├── .gitignore
│
├── docker/
│   └── apache/
│       └── 000-default.conf
│
├── public/
│   ├── index.php
│   ├── api/
│   │   ├── index.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   └── register.php
│   │   ├── projects/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   ├── update.php
│   │   │   └── delete.php
│   │   ├── tasks/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   ├── update.php
│   │   │   └── delete.php
│   │   ├── users/
│   │   │   ├── index.php
│   │   │   └── profile.php
│   │   └── analytics/
│   │       ├── dashboard.php
│   │       ├── time-tracking.php
│   │       └── reports.php
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css
│   │   │   ├── dashboard.css
│   │   │   ├── kanban.css
│   │   │   └── responsive.css
│   │   ├── js/
│   │   │   ├── main.js
│   │   │   ├── dashboard.js
│   │   │   ├── kanban.js
│   │   │   ├── time-tracker.js
│   │   │   └── analytics.js
│   │   └── images/
│   │       ├── logo.png
│   │       └── avatars/
│   │
│   └── views/
│       ├── layouts/
│       │   ├── header.php
│       │   ├── footer.php
│       │   └── sidebar.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── dashboard/
│       │   ├── index.php
│       │   └── overview.php
│       ├── projects/
│       │   ├── index.php
│       │   ├── view.php
│       │   ├── create.php
│       │   └── kanban.php
│       ├── tasks/
│       │   ├── index.php
│       │   ├── view.php
│       │   └── create.php
│       ├── users/
│       │   ├── index.php
│       │   └── profile.php
│       └── analytics/
│           ├── dashboard.php
│           └── reports.php
│
├── src/
│   ├── config/
│   │   ├── database.php
│   │   ├── config.php
│   │   └── constants.php
│   │
│   ├── core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   ├── View.php
│   │   └── Session.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── Task.php
│   │   ├── TimeEntry.php
│   │   ├── Team.php
│   │   └── Comment.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProjectController.php
│   │   ├── TaskController.php
│   │   ├── UserController.php
│   │   └── AnalyticsController.php
│   │
│   ├── middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   └── CorsMiddleware.php
│   │
│   └── utils/
│       ├── Validator.php
│       ├── FileUploader.php
│       ├── Logger.php
│       └── EmailSender.php
│
├── storage/
│   ├── logs/
│   ├── uploads/
│   │   ├── avatars/
│   │   └── attachments/
│   └── cache/
│
├── database/
│   ├── migrations/
│   │   ├── 001_create_users_table.sql
│   │   ├── 002_create_projects_table.sql
│   │   ├── 003_create_tasks_table.sql
│   │   ├── 004_create_time_entries_table.sql
│   │   ├── 005_create_teams_table.sql
│   │   ├── 006_create_comments_table.sql
│   │   └── 007_create_attachments_table.sql
│   │
│   ├── seeds/
│   │   ├── users_seed.sql
│   │   ├── projects_seed.sql
│   │   └── tasks_seed.sql
│   │
│   └── schema.sql
│
└── tests/
    ├── unit/
    ├── integration/
    └── api/