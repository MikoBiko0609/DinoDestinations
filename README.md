# Dino Destinations - Dinosaur Museum Locator

Dino Destinations is a web application that helps users find nearby dinosaur museums and exhibits. Users can enter a city and the app will display the top-rated dinosaur-related attractions in that area, along with their location, address, and rating information.

## Features
- Search for dinosaur museums and exhibits by city
- Interactive Google Maps integration
- Sort results by rating and distance
- Get directions to selected museums
- Responsive design for all devices

## Technologies Used

### Frontend
- HTML5
- CSS3
- Vanilla JavaScript
- Google Maps API integration

### Backend
- PHP
- Supabase - Database and environment management
- Google Maps APIs:
  - Places API
  - Geocoding API

### Deployment
- Frontend: Vercel
- Backend: Supabase
- Version Control: Git & GitHub

## Project Structure
```bash
project-root/
├── frontend/
│   ├── images/
│   ├── index.html
│   ├── login.js
│   ├── mainPage.css
│   ├── mainPage.html
│   ├── mainPage.js
│   ├── resultPage.css
│   ├── resultPage.html
│   └── welcomePage.css
│
├── backend/
│   ├── .dockerignore
│   ├── Dockerfile
│   ├── config.php
│   ├── login.php
│   ├── register.php
│   ├── render.yaml
│   ├── test.php
│   └── users.sql
│
└── README.md
