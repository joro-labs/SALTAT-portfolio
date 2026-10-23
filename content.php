<?php

const CONTENT_FILE = __DIR__ . '/data/content.json';

function categories() {
  return ['web' => 'Web', 'networking' => 'Networking', 'systems-security' => 'Systems & Security'];
}

function default_content() {
  $skills = [
    'Web Development'    => ['HTML', 'CSS', 'JavaScript', 'PHP', 'MySQL'],
    'Networking'         => ['IP Addressing', 'DNS & DHCP', 'TCP/IP', 'Network Troubleshooting'],
    'Systems & Security' => ['Windows Administration', 'Linux (Ubuntu)', 'PowerShell', 'PC Troubleshooting'],
    'Tools'              => ['Git & GitHub', 'VS Code', 'XAMPP', 'PC Building'],
  ];

  $projects = [
    [
      'title'   => 'Still Becoming',
      'cat'     => 'web',
      'img'     => 'still-becoming',
      'desc'    => 'A personal journal website where you can write, save and delete entries. Everything stays in your browser, so no account or database is needed.',
      'learned' => 'How to update the page with JavaScript and save data in the browser using localStorage.',
      'tech'    => 'HTML, CSS, JavaScript',
      'link'    => '',
    ],
    [
      'title'   => 'Community Reporting Platform',
      'cat'     => 'web',
      'img'     => 'community-reporting',
      'desc'    => 'A capstone concept where residents report local concerns and the barangay tracks each report until it is resolved.',
      'learned' => 'Planning a database and thinking about what residents and barangay staff each need to see.',
      'tech'    => 'PHP, MySQL, JavaScript',
      'link'    => '',
    ],
    [
      'title'   => 'PC Build & Troubleshooting',
      'cat'     => 'systems-security',
      'img'     => 'pc-build',
      'desc'    => 'Hands-on practice assembling desktop PCs and finding the cause of hardware and software problems.',
      'learned' => 'Checking that parts are compatible and troubleshooting step by step instead of guessing.',
      'tech'    => 'Hardware, Windows',
      'link'    => '',
    ],
    [
      'title'   => 'Office Network Simulation',
      'cat'     => 'networking',
      'img'     => 'office-network',
      'desc'    => 'A small office network designed in Cisco Packet Tracer with separate subnets for each department, a router, switches and a DHCP server.',
      'learned' => 'Subnetting, assigning static and dynamic IP addresses, and testing connections with ping and tracert.',
      'tech'    => 'Packet Tracer, TCP/IP, DHCP',
      'link'    => '',
    ],
    [
      'title'   => 'Home Wi-Fi Security Checkup',
      'cat'     => 'networking',
      'img'     => 'wifi-security',
      'desc'    => 'A checklist-based review of a home network: router settings, encryption type, default passwords and connected devices.',
      'learned' => 'Why WPA2/WPA3 matters, why default router credentials are risky, and how to spot unknown devices.',
      'tech'    => 'Router Admin, Wi-Fi, Network Security',
      'link'    => '',
    ],
    [
      'title'   => 'Ubuntu Server Lab',
      'cat'     => 'systems-security',
      'img'     => 'ubuntu-lab',
      'desc'    => 'A practice Ubuntu server running in a virtual machine, set up with user accounts, file permissions and basic firewall rules.',
      'learned' => 'Using the terminal, managing permissions with chmod, and allowing only the ports a service needs.',
      'tech'    => 'Linux, VirtualBox, UFW',
      'link'    => '',
    ],
    [
      'title'   => 'Password Strength Checker',
      'cat'     => 'web',
      'img'     => 'password-checker',
      'desc'    => 'A web page that rates a password as you type and shows tips such as adding length, numbers and symbols.',
      'learned' => 'Handling the input event, using regular expressions, and giving users instant feedback.',
      'tech'    => 'HTML, CSS, JavaScript',
      'link'    => '',
    ],
    [
      'title'   => 'Task Manager App',
      'cat'     => 'web',
      'img'     => 'task-manager',
      'desc'    => 'A simple to-do list where you can add, complete, edit and delete tasks, with a counter for what is still pending.',
      'learned' => 'Event delegation, updating the DOM dynamically, and keeping data saved between page visits.',
      'tech'    => 'HTML, CSS, JavaScript',
      'link'    => '',
    ],
    [
      'title'   => 'La Consolacion College Information Management System',
      'cat'     => 'web',
      'img'     => 'lcc-ims',
      'desc'    => 'A school information system for managing student records, course enrollment, class schedules and grades, with separate views for admin, teachers and students.',
      'learned' => 'Designing a relational database with linked tables, role-based access with PHP sessions, and building CRUD pages for records.',
      'tech'    => 'PHP, MySQL, JavaScript, XAMPP',
      'link'    => '',
    ],
    [
      'title'   => 'Local Concern Tracker',
      'cat'     => 'web',
      'img'     => 'concern-tracker',
      'desc'    => 'A monitoring dashboard where barangay staff see local concerns such as broken streetlights, flooding and garbage collection. Each report gets a tracking number and a status: Pending, In Progress or Resolved.',
      'learned' => 'Generating tracking numbers, updating report status, and showing counts per status so staff can see what needs attention first.',
      'tech'    => 'PHP, MySQL, JavaScript',
      'link'    => '',
    ],
    [
      'title'   => 'Student Attendance Monitoring System',
      'cat'     => 'web',
      'img'     => 'attendance-monitor',
      'desc'    => 'A class attendance system where a teacher marks students present, late or absent each meeting and views attendance summaries per student and per subject.',
      'learned' => 'Saving daily records in a database, filtering by date and subject, and computing attendance percentages with SQL.',
      'tech'    => 'PHP, MySQL, HTML, CSS',
      'link'    => '',
    ],
    [
      'title'   => 'Computer Lab Inventory & Monitoring',
      'cat'     => 'systems-security',
      'img'     => 'lab-inventory',
      'desc'    => 'A record of computers and equipment in a school lab with their specs, condition and borrow/return log, so broken or missing units are easy to spot.',
      'learned' => 'Keeping an organized asset list, documenting hardware issues, and tracking who is responsible for each unit.',
      'tech'    => 'PHP, MySQL, Hardware',
      'link'    => '',
    ],
  ];

  return ['skills' => $skills, 'projects' => $projects];
}

function load_content() {
  if (is_file(CONTENT_FILE)) {
    $data = json_decode((string)file_get_contents(CONTENT_FILE), true);
    if (is_array($data) && isset($data['skills'], $data['projects']) && is_array($data['skills']) && is_array($data['projects'])) {
      return $data;
    }
  }
  return default_content();
}

function save_content($content) {
  $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  return $json !== false && file_put_contents(CONTENT_FILE, $json, LOCK_EX) !== false;
}