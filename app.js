
const state = {
  currentUser: null,
  showLogin: false,
  showRegistration: false,
  loginRolePreset: "student",
  currentView: "dashboard",
  students: [
    { id: "ST-001", name: "Ali Raza", route: "R-01", feeStatus: "Paid", feeAmount: 4500, lastPaymentDate: "2026-03-10" },
    { id: "ST-002", name: "Ahmed Khan", route: "R-02", feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" },
    { id: "ST-003", name: "Hassan Ali", route: "R-03", feeStatus: "Paid", feeAmount: 4800, lastPaymentDate: "2026-03-06" },
    { id: "ST-004", name: "Umar Farooq", route: "R-04", feeStatus: "Unpaid", feeAmount: 5300, lastPaymentDate: "-" },
    { id: "ST-005", name: "Muhammad Bilal", route: "R-01", feeStatus: "Paid", feeAmount: 4500, lastPaymentDate: "2026-03-13" },
    { id: "ST-006", name: "Usman Tariq", route: "R-02", feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" },
    { id: "ST-007", name: "Hamza Khalid", route: "R-03", feeStatus: "Paid", feeAmount: 4800, lastPaymentDate: "2026-03-08" },
    { id: "ST-008", name: "Saad Ahmed", route: "R-04", feeStatus: "Unpaid", feeAmount: 5300, lastPaymentDate: "-" },
    { id: "ST-009", name: "Zain Ali", route: "R-01", feeStatus: "Paid", feeAmount: 4500, lastPaymentDate: "2026-03-12" },
    { id: "ST-010", name: "Danish Iqbal", route: "R-02", feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" },
    { id: "ST-011", name: "Talha Mahmood", route: "R-03", feeStatus: "Paid", feeAmount: 4800, lastPaymentDate: "2026-03-09" },
    { id: "ST-012", name: "Awais Raza", route: "R-04", feeStatus: "Unpaid", feeAmount: 5300, lastPaymentDate: "-" },
    { id: "ST-013", name: "Faizan Ali", route: "R-01", feeStatus: "Paid", feeAmount: 4500, lastPaymentDate: "2026-03-11" },
    { id: "ST-014", name: "Imran Shah", route: "R-02", feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" },
    { id: "ST-015", name: "Sheraz Ahmed", route: "R-03", feeStatus: "Paid", feeAmount: 4800, lastPaymentDate: "2026-03-07" },
    { id: "ST-016", name: "Noman Khan", route: "R-04", feeStatus: "Unpaid", feeAmount: 5300, lastPaymentDate: "-" },
    { id: "ST-017", name: "Haris Ali", route: "R-01", feeStatus: "Paid", feeAmount: 4500, lastPaymentDate: "2026-03-14" },
    { id: "ST-018", name: "Abdullah Tariq", route: "R-02", feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" },
    { id: "ST-019", name: "Fahad Hassan", route: "R-03", feeStatus: "Paid", feeAmount: 4800, lastPaymentDate: "2026-03-05" },
    { id: "ST-020", name: "Mustafa Raza", route: "R-04", feeStatus: "Unpaid", feeAmount: 5300, lastPaymentDate: "-" }
  ],
  routes: [
    {
      id: "R-01",
      bookNo: 1,
      catalogTitle: "BUS ROUTE FROM SAMUNDRI ROAD",
      name: "Samundri Road Four Season",
      yearlyFare: "13,750 × 4 = PKR 55,000",
      academicYear: "2025–2026",
      stops: [
        "Four Session Society (SAMUNDRI ROAD)",
        "D-type",
        "Novelty Pull / Samundri Road",
        "GTS Square",
        "Jhal Square",
        "Saleemi Square (SATIANA ROAD)",
        "Gate Square (SATIANA ROAD)",
        "Toll tex Square (SATIANA ROAD)",
        "Fish farm (SATIANA ROAD)",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-02",
      bookNo: 2,
      catalogTitle: "BUS ROUTE FROM SARGODHA ROAD",
      name: "Sargodha Road",
      yearlyFare: "13,750 × 4 = PKR 55,000",
      academicYear: "2025–2026",
      stops: [
        "Lassani Puli (SARGODHA ROAD)",
        "Allied Moar (SARGODHA ROAD)",
        "Millit Square (SARGODHA ROAD)",
        "Jamia Chistia Square (SARGODHA ROAD)",
        "General Bus Stand (SARGODHA ROAD)",
        "Chanab Club",
        "Tariqabad Pull",
        "Jarawala Road",
        "Degree College",
        "Jalvi Market (JARANWALA ROAD)",
        "Mukuana Byepass (JARANWALA ROAD)",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-03",
      bookNo: 3,
      catalogTitle: "BUS ROUTE FROM MILLAT TOWN",
      name: "Millat Town",
      yearlyFare: "13,750 × 4 = PKR 55,000",
      academicYear: "2025–2026",
      stops: [
        "Hassan Square (MILLAT ROAD)",
        "Green Town Square (MILLAT ROAD)",
        "Noor pur (SHEIKHUPURA ROAD)",
        "Millat Square",
        "Hajiabad (SHEIKHUPURA ROAD)",
        "Nishtaabad Flyover (SHEIKHUPURA ROAD)",
        "Kashmir Pull (CANAL ROAD)",
        "Degree College (JARANWALA ROAD)",
        "Superior College Jaranwala Road",
        "Jalvi Market (JARANWALA ROAD)",
        "Lower Canal (JARANWALA ROAD / SATIANA ROAD)",
        "Fish Farm (SATIANA ROAD)",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-04",
      bookNo: 4,
      catalogTitle: "BUS ROUTE FROM SADER BAZAR GM ABAD",
      name: "GM Abad",
      yearlyFare: "13,750 × 4 = PKR 55,000",
      academicYear: "2025–2026",
      stops: [
        "Kabootran Wala Chowk (G.M. ABAD)",
        "Sadar Bazar (Bara Qabristan)",
        "Gulburg (POLICE STATION SQUARE)",
        "Jinnah Colony Gate",
        "Nishat Cinema Square",
        "Independent College",
        "Chanab Square (JHANG ROAD)",
        "Superior College Kotwali Road",
        "Katchary Bazar Square",
        "Gumtai Square",
        "GTS Square",
        "Jhal Square (SATIANA ROAD)",
        "Saleemi Square (SATIANA ROAD)",
        "Gate Square (SATIANA ROAD)",
        "Toll tex Square (SATIANA ROAD)",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-05",
      bookNo: 5,
      catalogTitle: "BUS ROUTE FROM SAMUNDRI – TANDALAWALA",
      name: "Samundri – Tandlianwala",
      yearlyFare: "17,500 × 4 = PKR 70,000",
      academicYear: "2025–2026",
      stops: [
        "Gojra moar",
        "Govt College Samundri",
        "Jala Moar",
        "Tandalawala Pull",
        "426 Adda (Stop) [FSD-OKARA ROAD]",
        "Bismilla Square [FSD-OKARA ROAD]",
        "Kiker Stop / Shajwal Stop [FSD-OKARA ROAD]",
        "Jahal [FSD-OKARA ROAD]",
        "Satiana Banglow",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-06",
      bookNo: 6,
      catalogTitle: "BUS ROUTE FROM JARANWALA",
      name: "Jaranwala",
      yearlyFare: "16,250 × 4 = PKR 65,000",
      academicYear: "2025–2026",
      stops: [
        "Lari Adda [JARANWALA]",
        "Fowara Square [JARANWALA]",
        "Lucker Mandi Square [JARANWALA]",
        "Defense View [JARANWALA]",
        "Lahore More [JARANWALA]",
        "Jaranwala Jahal pull",
        "Booty Wali Jahal [JARANWALA–SATIANA ROAD]",
        "Satiana Banglow Pull",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-07",
      bookNo: 7,
      catalogTitle: "BUS ROUTE FROM CHINIOT",
      name: "Chiniot",
      yearlyFare: "20,000 × 4 = PKR 80,000",
      academicYear: "2025–2026",
      stops: [
        "Aqsa Square [CHINIOT]",
        "Tashil Square [CHINIOT]",
        "Superior College Chinot",
        "FAST University",
        "University Town (SARGODHA ROAD)",
        "Lassani puli (SARGODHA ROAD)",
        "Allied moar (SARGODHA ROAD)",
        "Agriculture University",
        "Fish form (SATIANA ROAD)",
        "SUPERIOR UNIVERSITY"
      ]
    },
    {
      id: "R-08",
      bookNo: 8,
      catalogTitle: "BUS ROUTE FROM SHAHKOT",
      name: "Shahkot",
      yearlyFare: "17,500 × 4 = PKR 70,000",
      academicYear: "2025–2026",
      stops: [
        "Mehar Square (SHAHKOT)",
        "Superior College Shahkot",
        "61 Check",
        "Johal",
        "Superior College Khurrianwala",
        "UET university [BYPASS]",
        "Mukuana Bypass",
        "SUPERIOR UNIVERSITY"
      ]
    }
  ],
  buses: [
    { id: "BUS-01", model: "Daewoo 2022", capacity: 50, route: "R-01" },
    { id: "BUS-02", model: "Hino 2020", capacity: 48, route: "R-02" },
    { id: "BUS-03", model: "Yutong 2023", capacity: 52, route: "R-03" },
    { id: "BUS-04", model: "Mazda 2019", capacity: 45, route: "R-04" },
    { id: "BUS-05", model: "Master 2021", capacity: 47, route: "R-05" },
    { id: "BUS-06", model: "Daewoo 2020", capacity: 50, route: "R-06" },
    { id: "BUS-07", model: "Hino 2022", capacity: 46, route: "R-07" },
    { id: "BUS-08", model: "Yutong 2021", capacity: 51, route: "R-08" }
  ],
  focalPersons: [
    { id: "FP-01", name: "Sir Ahmed Bilal", route: "R-01" },
    { id: "FP-02", name: "Madam Hina Saleem", route: "R-02" },
    { id: "FP-03", name: "Sir Imran Yousaf", route: "R-03" },
    { id: "FP-04", name: "Madam Sana Javed", route: "R-04" },
    { id: "FP-05", name: "Sir Bilal Hussain", route: "R-05" },
    { id: "FP-06", name: "Madam Ayesha Khan", route: "R-06" },
    { id: "FP-07", name: "Sir Usman Ali", route: "R-07" },
    { id: "FP-08", name: "Madam Rabia Noor", route: "R-08" },
    { id: "FP-08", name: "Gohar ziab Gill", route: "R-08" },
    { id: "FP-08", name: "Maham", route: "R-08" },
    { id: "FP-08", name: "Shahda perveen", route: "R-08" },
    { id: "FP-08", name: "Ashraf Gill", route: "R-08" },
    { id: "FP-01", name: "Sir Ahmed Bilal", route: "R-01" },
    { id: "FP-02", name: "Madam Hina Saleem", route: "R-02" },
    { id: "FP-03", name: "Sir Imran Yousaf", route: "R-03" },
    { id: "FP-04", name: "Madam Sana Javed", route: "R-04" },
    { id: "FP-05", name: "Sir Bilal Hussain", route: "R-05" },
    { id: "FP-06", name: "Madam Ayesha Khan", route: "R-06" },
    { id: "FP-07", name: "Sir Usman Ali", route: "R-07" },
    { id: "FP-08", name: "Madam Rabia Noor", route: "R-08" },
    { id: "FP-08", name: "Gohar ziab Gill", route: "R-08" },
    { id: "FP-08", name: "Maham", route: "R-08" },
    { id: "FP-08", name: "Shahda perveen", route: "R-08" },
    { id: "FP-08", name: "Ashraf Gill", route: "R-08" }
  ],
  attendance: [],
  feeInstallments: [
    { studentId: "ST-001", installment: 1, amount: 13750, dueDate: "2025-09-15", status: "Paid", paymentDate: "2025-09-12" },
    { studentId: "ST-001", installment: 2, amount: 13750, dueDate: "2025-12-15", status: "Paid", paymentDate: "2025-12-10" },
    { studentId: "ST-001", installment: 3, amount: 13750, dueDate: "2026-03-15", status: "Paid", paymentDate: "2026-03-10" },
    { studentId: "ST-001", installment: 4, amount: 13750, dueDate: "2026-06-15", status: "Pending", paymentDate: "-" },
    { studentId: "ST-002", installment: 1, amount: 13750, dueDate: "2025-09-15", status: "Paid", paymentDate: "2025-09-14" },
    { studentId: "ST-002", installment: 2, amount: 13750, dueDate: "2025-12-15", status: "Paid", paymentDate: "2025-12-20" },
    { studentId: "ST-002", installment: 3, amount: 13750, dueDate: "2026-03-15", status: "Unpaid", paymentDate: "-" },
    { studentId: "ST-002", installment: 4, amount: 13750, dueDate: "2026-06-15", status: "Pending", paymentDate: "-" },
    { studentId: "ST-003", installment: 1, amount: 13750, dueDate: "2025-09-15", status: "Paid", paymentDate: "2025-09-10" },
    { studentId: "ST-003", installment: 2, amount: 13750, dueDate: "2025-12-15", status: "Paid", paymentDate: "2025-12-08" },
    { studentId: "ST-003", installment: 3, amount: 13750, dueDate: "2026-03-15", status: "Paid", paymentDate: "2026-03-06" },
    { studentId: "ST-003", installment: 4, amount: 13750, dueDate: "2026-06-15", status: "Pending", paymentDate: "-" }
  ],
  sort: { key: "date", order: "desc" },
  busPositions: {},
  trackingInterval: null,
  notifications: [
    { id: 1, type: "delay", title: "Bus Delay", message: "Route R-01 is running 15 mins late due to traffic.", route: "R-01", time: "10:30 AM", priority: "medium", read: false },
    { id: 2, type: "info", title: "Holiday Notice", message: "Transport will be closed on 14th April due to Baisakhi.", route: "All", time: "Yesterday", priority: "low", read: true }
  ],
  notificationFilter: "",
  reportSearchQuery: "",
  accountsSearchQuery: "",
  focalSearchQuery: "",
  focalUnpaidSearchQuery: "",
  focalAttendanceSearchQuery: "",
  activeSearchInput: null,
  unreadNotifCount: 0,
  complaints: [],
  transportRatings: [],
  registrationRequests: [
    // Add some default users for testing
    { id: "admin001", username: "admin", password: "admin123", name: "Admin User", role: "admin", status: "approved", createdAt: "2026-01-01" },
    { id: "focal001", username: "focal", password: "focal123", name: "Focal Person", role: "focal", status: "approved", createdAt: "2026-01-01" },
    { id: "accounts001", username: "accounts", password: "accounts123", name: "Accounts Staff", role: "accounts", status: "approved", createdAt: "2026-01-01" },
    { id: "student001", username: "student", password: "student123", name: "Student User", role: "student", studentId: "ST-001", status: "approved", createdAt: "2026-01-01" },
    { id: "maham001", username: "maham", password: "maham123", name: "Maham User", role: "student", studentId: "ST-006", status: "approved", createdAt: "2026-01-01" }
  ]
};

const roleMenus = {
  student: [
    { id: "dashboard", label: "Dashboard" },
    { id: "register", label: "Change Route" },
    { id: "attendance", label: "Attendance Record" },
 
    { id: "feedback", label: "Transport Feedback" },
    { id: "notifications", label: "Notifications" },
    { id: "fees", label: "Fee Status / Payment" }
  ],
  focal: [
    { id: "dashboard", label: "Dashboard" },
    { id: "fee_reminders", label: "Fee Reminders" },
    { id: "send_notif", label: "Send Update" },
    { id: "attendance", label: "Attendance History" },
    { id: "unpaid", label: "Unpaid Students" }
  ],
  accounts: [
    { id: "dashboard", label: "Dashboard" },
    { id: "payments", label: "Process Payments" },
    { id: "reports", label: "Payment Reports" }
  ],
  admin: [
    { id: "dashboard", label: "Dashboard" },
    { id: "admin_dashboard", label: "Admin Control Center " },
    { id: "requests", label: "Registration Requests" },
    { id: "buses", label: "Bus Management" },
    { id: "routes", label: "Route Management" },
    { id: "complaints", label: "Complaints" },
    { id: "send_notif", label: "Post Notice" },
    { id: "focals", label: "Focal Person Management" },
    { id: "reports", label: "Payment Reports" }
  ]
};

const app = document.getElementById("app");
const modalBackdrop = document.getElementById("modalBackdrop");
const modalTitle = document.getElementById("modalTitle");
const modalBody = document.getElementById("modalBody");
const closeModalBtn = document.getElementById("closeModalBtn");
const universityLogoSrc = "download.jfif";
const universityBusImageSrc = "6a-jiwyBPOeJOH13Wb6HyuXivsT9hD93BWjic-Dc2sgWq8BJmkeQMalOilnb3aHy4_dB_h4oTplRWUdZX6UwOjCmoFYIcctMDTjVU8eu-ayci3ChJXXpUvguRe1bxAL489SBr6uKoB5c6WsViAJu12NyNLdUQhTTH8H6agm61pZs7TlfjtZyyAkIfIcaPWVu.jfif";
const universityCampusImageSrc = "FaislabadCampus-Superior-University.webp";
/** Campus clip — same folder as index.html */
const transportVideoSrc = encodeURI("WhatsApp Video 2026-03-27 at 10.35.55 PM.mp4");
const socialInstagram = "https://www.instagram.com/superior_university_faisalabad?igsh=ZGpjcnVxY2w1OTV";
const socialFacebook = "https://www.facebook.com/SuperiorUniversityFaisalabad";
const officialWebsite = "https://www.superior.edu.pk/";
const socialLinkedin = "https://www.linkedin.com/company/superioruniversityofficial/posts/?feedView=all";
const socialYoutube = "https://youtube.com/@thesuperioruniversity?si=FjaEcgMdb1H8Gv2L";
const socialTwitter = "https://twitter.com/Supuniofficial?t=9WeoXfu-ZzJOtHs7j2WvsA&s=09";
/** Place Transport Book (7).pdf next to index.html */
const transportBookPdfSrc = encodeURI("Transport Book (7).pdf");
const navigationStack = [];
let lastSwipeBackAt = 0;

closeModalBtn.addEventListener("click", () => closeModal());
modalBackdrop.addEventListener("click", (e) => {
  if (e.target === modalBackdrop) closeModal();
});

function roleLabel(role) {
  const map = {
    student: "Student",
    focal: "Focal Person",
    accounts: "Accounts Staff",
    admin: "Transport Head (Admin)"
  };
  return map[role] || role;
}

function findUserByUsername(username) {
  return state.registrationRequests.find(user => user.username === username);
}

function generateRequestId() {
  return "req_" + Date.now() + "_" + Math.random().toString(36).substr(2, 9);
}

function routeById(id) {
  return state.routes.find((r) => r.id === id);
}

function busByRoute(routeId) {
  return state.buses.find((b) => b.route === routeId);
}

function filterNotifications(query) {
  if (!query || !query.trim()) return state.notifications;
  const q = query.trim().toLowerCase();
  return state.notifications.filter((n) => {
    return (
      `${n.title} ${n.message} ${n.route} ${n.type}`.toLowerCase().includes(q)
    );
  });
}

function deleteNotification(id) {
  const role = state.currentUser?.role;
  if (role !== "admin" && role !== "focal") {
    openModal("Access Denied", "Only Admin or Focal Person can delete notifications.");
    return;
  }
  confirmDelete(() => {
    state.notifications = state.notifications.filter((n) => n.id !== Number(id));
    state.unreadNotifCount = state.notifications.filter((n) => n.read === false).length;
    saveState();
    render();
  });
}

function filterReportStudents(query) {
  if (!query || !query.trim()) return state.students;
  const q = query.trim().toLowerCase();
  return state.students.filter((s) => {
    const routeName = studentRouteName(s.route).toLowerCase();
    const att = state.attendance.find((a) => a.studentId === s.id) || { date: "", status: "" };
    return (
      s.id.toLowerCase().includes(q) ||
      s.name.toLowerCase().includes(q) ||
      s.route.toLowerCase().includes(q) ||
      routeName.includes(q) ||
      s.feeStatus.toLowerCase().includes(q) ||
      att.status.toLowerCase().includes(q) ||
      att.date.toLowerCase().includes(q)
    );
  });
}

function studentRouteName(routeId) {
  const route = routeById(routeId);
  if (!route) return routeId;
  return `${route.id} · Route ${route.bookNo}: ${route.name}`;
}

function openModal(title, contentHtml) {
  modalTitle.textContent = title;
  modalBody.innerHTML = contentHtml;
  modalBackdrop.classList.remove("hidden");
}

function captureNavigationState() {
  if (state.currentUser) {
    return {
      type: "shell",
      currentUser: { ...state.currentUser },
      currentView: state.currentView
    };
  }
  if (state.showLogin) {
    return {
      type: "login",
      loginRolePreset: state.loginRolePreset
    };
  }
  return { type: "landing" };
}

function restoreNavigationState(snapshot) {
  if (!snapshot) return;
  if (snapshot.type === "shell") {
    state.currentUser = { ...snapshot.currentUser };
    state.showLogin = false;
    state.currentView = snapshot.currentView || "dashboard";
    return;
  }
  if (snapshot.type === "login") {
    state.currentUser = null;
    state.showLogin = true;
    state.loginRolePreset = snapshot.loginRolePreset || "student";
    state.currentView = "dashboard";
    return;
  }
  state.currentUser = null;
  state.showLogin = false;
  state.currentView = "dashboard";
}

function pushNavigationState() {
  navigationStack.push(captureNavigationState());
  if (navigationStack.length > 60) navigationStack.shift();
  forwardStack = []; // Clear forward stack when navigating to new page
}

function navigateBackOneStep() {
  const snapshot = navigationStack.pop();
  if (!snapshot) return;
  // Save current state to forward stack before going back
  forwardStack.push(captureNavigationState());
  if (forwardStack.length > 60) forwardStack.shift();
  restoreNavigationState(snapshot);
  render();
}

let forwardStack = [];

function navigateForwardOneStep() {
  const snapshot = forwardStack.pop();
  if (!snapshot) return;
  // Save current state to navigation stack before going forward
  navigationStack.push(captureNavigationState());
  if (navigationStack.length > 60) navigationStack.shift();
  restoreNavigationState(snapshot);
  render();
}

// Touchpad two-finger horizontal swipe support for back and forward navigation.
window.addEventListener("wheel", (event) => {
  const horizontalSwipe = Math.abs(event.deltaX) > Math.abs(event.deltaY) * 1.25;
  const swipeThreshold = 70;
  if (!horizontalSwipe || Math.abs(event.deltaX) < swipeThreshold) return;
  
  const now = Date.now();
  if (now - lastSwipeBackAt < 700) return;
  lastSwipeBackAt = now;
  
  // Left swipe (negative deltaX) = go back, Right swipe (positive deltaX) = go forward
  if (event.deltaX < 0) {
    navigateBackOneStep();
  } else {
    navigateForwardOneStep();
  }
}, { passive: true });

function render() {
  if (!state.currentUser) {
    if (state.showRegistration) {
      renderRegistration();
    } else if (state.showLogin) {
      renderLogin();
    } else {
      renderLandingPage();
    }
    return;
  }
  renderShell();
}

function renderLandingPage() {
  const stats = {
    buses: state.buses.length,
    routes: state.routes.length,
    students: state.students.length,
    attendance: state.attendance.filter((a) => a.status === "Present").length
  };

  app.innerHTML = `
    <div class="landing">
      <header class="landing-header">
        <a href="${officialWebsite}" target="_blank" rel="noopener noreferrer" class="landing-brand" style="text-decoration:none; color:inherit; display:flex; gap:10px; align-items:center;">
          <img class="brand-logo" src="${universityLogoSrc}" alt="Superior University logo" />
          <div>
            <h1>Superior University Faisalabad</h1>
            <p>University Transport Automation Portal</p>
          </div>
        </a>
        <nav class="landing-nav">
          <a href="#home">Home</a>
          <a href="#about">About Transport</a>
          <a href="#transport-book">Transport book</a>
          <a href="#routes">Routes</a>
          <a href="#features">Features</a>
          <a href="contact.html">Contact</a>
          <button id="openLoginBtn" class="btn-primary nav-login-btn" type="button">Login</button>
        </nav>
      </header>

      <section id="home" class="hero-banner">
        <img class="hero-bg-image" src="${universityBusImageSrc}" alt="Superior University bus" />
        <div class="hero-overlay"></div>
        <div class="hero-content">
          <h2>Superior University Faisalabad Transport Automation Portal</h2>
          <h3>Safe, Reliable and Smart Transportation for Students</h3>
          <p>This system helps students and transport staff manage university bus services efficiently including registration, routes, attendance and fee management.</p>
<div class="hero-actions">
           
            <button class="btn-primary role-btn role-student" data-open-role="student">Apply for Transport</button>
          </div>
        </div>
          </div>
        </div>
      </section>

      <section id="about" class="landing-section two-col">
        <div class="section-image">
          <img src="${universityBusImageSrc}" alt="Superior University bus service">
        </div>
        <div>
          <h2>About Transport Service</h2>
          <p>Superior University Faisalabad provides safe and organized transport services for students across multiple city routes. The University Transport Automation Portal allows students to register for transport, select routes, track attendance and manage transport fees efficiently.</p>
        </div>
      </section>

      <section id="features" class="landing-section">
        <h2>System Features</h2>
        <div class="feature-grid">
          ${[
            ["Student Registration", "Students can register and select transport routes online."],
            ["Bus & Route Management", "Transport team manages buses, routes and assignments."],
            ["Attendance Tracking", "Focal persons can mark and monitor attendance records."],
            ["Fee Management", "Accounts staff updates paid/unpaid status and dues."],
            ["Seat Availability", "Route wise transport planning with capacity details."],
            ["Role Based Dashboards", "Separate access for student, staff, accounts and admin."],
            ["Reports & Analytics", "Generate insights for attendance, routes and payments."],
            ["Notifications System", "Send reminders to unpaid and absent students."]
          ].map(([title, desc]) => `
            <article class="feature-card">
              <h3>${title}</h3>
              <p>${desc}</p>
            </article>
          `).join("")}
        </div>
      </section>

      <section class="landing-section">
        <h2>Transport Facilities</h2>
        <div class="transport-facilities-grid">
          <div class="facilities-list">
            <ul class="bullet-list">
              <li>Multiple Bus Routes</li>
              <li>Experienced Drivers</li>
              <li>Fixed Timetable</li>
              <li>Safe Transport Service</li>
              <li>Comfortable Buses</li>
              <li>City Wide Coverage</li>
            </ul>
          </div>
          <div class="facilities-map">
            <h3>Route Coverage Map</h3>
            <div id="transportMap" class="map-container">
              <iframe 
                src="https://www.openstreetmap.org/export/embed.html?bbox=73.0485%2C31.4180%2C73.1585%2C31.5180&layer=mapnik&marker=31.4680%2C73.1035"
                width="100%" 
                height="300" 
                frameborder="0" 
                style="border: 1px solid #ccc; border-radius: 8px;"
                allowfullscreen>
              </iframe>
            </div>
           
          </div>
        </div>
      </section>

      <section class="landing-section">
        <h2>User Access Levels</h2>
        <div class="role-grid">
          <article class="feature-card"><h3>Student</h3><p>Register for transport, view assigned bus, check fee status, view attendance.</p></article>
          <article class="feature-card"><h3>Focal Person</h3><p>Mark attendance, view assigned students, check unpaid students.</p></article>
          <article class="feature-card"><h3>Accounts Staff</h3><p>Process transport fees and generate payment reports.</p></article>
          <article class="feature-card"><h3>Transport Admin</h3><p>Manage buses, routes, focal assignments and system reports.</p></article>
        </div>
      </section>

      <section class="landing-section">
        <h2>Transport Overview</h2>
        <div class="stats-grid">
          <article class="feature-card"><h3>Total Buses</h3><p class="big-stat">${stats.buses}</p></article>
          <article class="feature-card"><h3>Total Routes</h3><p class="big-stat">${stats.routes}</p></article>
          <article class="feature-card"><h3>Registered Students</h3><p class="big-stat">${stats.students}</p></article>
          <article class="feature-card"><h3>Daily Attendance</h3><p class="big-stat">${stats.attendance}</p></article>
        </div>
      </section>

      <section id="transport-book" class="landing-section transport-book-block">
        <h2>Transport Book (${state.routes[0] ? state.routes[0].academicYear : "2025–2026"})</h2>
        <p class="transport-book-lead">Official Superior University Faisalabad transport guide—same route list and yearly fares as <strong>Transport Book (7)</strong>. Students should keep the PDF for enrolment, route selection, and pickup-point reference.</p>
        <ul class="transport-book-points">
          <li>All ${state.routes.length} routes with full stop sequences (below)</li>
          <li>Yearly fare slabs per route (quarterly instalments as per book)</li>
          <li>Download for offline use on phone or laptop</li>
        </ul>
        <a href="${transportBookPdfSrc}" class="btn-primary transport-book-download" download target="_blank" rel="noopener noreferrer">Open / download Transport Book (PDF)</a>
      
      </section>

      <section id="routes" class="landing-section">
        <h2>Available Bus Routes (from Transport Book)</h2>
        <p class="routes-intro">Academic session ${state.routes[0] ? state.routes[0].academicYear : "2025–2026"} · Tap <strong>View all stops</strong> for the full pickup list.</p>
        <div class="feature-grid route-book-grid">
          ${state.routes.map((r) => `
            <article class="feature-card route-book-card">
              <h3>Route ${r.bookNo} · ${r.name}</h3>
              <p class="route-book-catalog">${r.catalogTitle}</p>
              <p class="route-fare"><strong>Yearly fare:</strong> ${r.yearlyFare}</p>
              <p class="route-stop-count">${r.stops.length} stops including Superior University</p>
              <button type="button" class="btn-soft route-preview-btn" data-route-preview="${r.id}">View all stops</button>
            </article>
          `).join("")}
        </div>
      </section>

      <section class="landing-section cta-block">
        <h2>Apply for University Transport</h2>
        <p>Students can apply for transport service and select their preferred route.</p>
        <button class="btn-primary cta-btn" data-open-role="student">Apply Now</button>
      </section>

      <section class="landing-section">
        <h2>Transport Rules</h2>
        <ul class="bullet-list">
          <li>Students must carry ID card</li>
          <li>Monthly fee must be paid on time</li>
          <li>Follow bus discipline</li>
          <li>No route change without approval</li>
        </ul>
      </section>

      <div id="campus-video" class="campus-video-section" aria-label="Campus video">
        <div class="campus-video-inner">
          <div class="campus-video-text">
            <h3 class="campus-video-title">A little campus view</h3>
            <p class="campus-video-lead">A short look at Superior University Faisalabad—main buildings, open spaces, and the campus atmosphere our students see every day. This clip sits alongside our transport portal so visitors can picture where buses arrive and where academic life happens.</p>
            <ul class="campus-video-points">
              <li>Campus architecture, walkways, and key facades</li>
              <li>Greenery and outdoor areas around the university</li>
              <li>Connects visually with branded transport and daily student flow</li>
            </ul>
          </div>
          <div class="campus-video-media">
            <div class="campus-video-hover-wrap" title="">
              <div class="campus-video-frame">
                <video id="campus-hover-video" controls playsinline muted preload="metadata" poster="${universityCampusImageSrc}">
                  <source src="${transportVideoSrc}" type="video/mp4" />
                </video>
              </div>
          
            </div>
            <p class="campus-video-meta">Superior University Faisalabad · Campus glimpse</p>
          </div>
        </div>
      </div>

      <section class="landing-section" id="faq">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-section">
          ${[
            ["How do I register for transport services?", "Visit the registration desk at the transport office or apply online through our portal."],
            ["What are the transport fee payment methods?", "We accept cash, bank drafts, and online transfers. Payment can be made monthly or quarterly."],
            ["How can I report a bus-related issue?", "Contact our helpline at 111-SUPER-1 or use the complaint form available on our website."],
            ["Are there any discounts available?", "Yes, we offer sibling discounts and early payment discounts. Contact the accounts office for details."],
            ["What if I miss my bus?", "Contact the bus incharge immediately. We'll arrange alternative transport if available."],
            ["How do I change my route?", "Submit a route change request form at the transport office at least 3 days before the change."]
          ].map(([q, a]) => `
            <article class="faq-item">
              <button class="faq-question" type="button">
                <h4>${q}</h4>
                <span class="faq-icon"><i class="fas fa-plus"></i></span>
              </button>
              <div class="faq-answer">
                <p>${a}</p>
              </div>
            </article>
          `).join("")}
        </div>
      </section>


      <footer class="landing-footer" id="footer-contact">
        <div class="footer-main">
          <div class="footer-col footer-brand-block">
            <strong class="footer-title">Superior University Faisalabad</strong>
            <span class="footer-sub">University Transport Automation Portal</span>
          </div>
          <div class="footer-col footer-transport-block">
            <span class="footer-block-heading">Transport Office Contact</span>
            <span class="footer-transport-line"><strong>Transport Office</strong></span>
            <span class="footer-transport-line">Superior University Faisalabad</span>
            <span class="footer-transport-line"><strong>Phone:</strong> +92-41-1234567</span>
            <span class="footer-transport-line"><strong>Email:</strong> <a class="footer-link" href="mailto:transport@superior.edu.pk">transport@superior.edu.pk</a></span>
            <span class="footer-transport-line"><strong>Office Hours:</strong> 9 AM – 4 PM</span>
          </div>
          <div class="footer-col footer-social-block">
            <span class="footer-follow">Follow us</span>
            <div class="footer-social-icons">
              <a class="social-icon social-instagram" href="${socialInstagram}" target="_blank" rel="noopener noreferrer" aria-label="Superior University Faisalabad on Instagram">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </a>
              <a class="social-icon social-facebook" href="${socialFacebook}" target="_blank" rel="noopener noreferrer" aria-label="Superior University Faisalabad on Facebook">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a class="social-icon social-youtube" href="${socialYoutube}" target="_blank" rel="noopener noreferrer" aria-label="Superior University Faisalabad on YouTube">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
              <a class="social-icon social-linkedin" href="${socialLinkedin}" target="_blank" rel="noopener noreferrer" aria-label="Superior University Faisalabad on LinkedIn">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.475-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </a>
              <a class="social-icon social-twitter" href="${socialTwitter}" target="_blank" rel="noopener noreferrer" aria-label="Superior University Faisalabad on Twitter">
                <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
            </div>
          </div>
        </div>
        <div class="footer-bar">© 2026 Superior University Faisalabad · All rights reserved</div>
      </footer>
    </div>
  `;

  bindLandingActions();
}

function renderLogin() {
  app.innerHTML = `
    <div class="auth-wrap">
      <div class="auth-card">
        <div class="hero">
          <div class="hero-brand-mark">
            <img class="hero-logo" src="${universityLogoSrc}" alt="Superior University logo" />
          </div>
          <h1>University Transport Automation Portal</h1>
          <p>Faisalabad transport operations portal</p>
        </div>
        <form id="loginForm" class="form">
          <div>
            <label for="username">UserID</label>
            <input id="username" placeholder="Enter your UserID" required />
            <label for="Password">Password</label>
            <input id="password" type="password" placeholder="Enter your password" required />
          </div>
          <button class="btn-primary" type="submit">Login</button>
          <button id="backToHomeBtn" class="btn-soft" type="button">Back to Home</button>
          <p class="auth-link">Don't have an account? <a href="#" id="toggleRegistration">Register Here</a></p>
        </form>
      </div>
    </div>
  `;

  document.getElementById("loginForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;
    if (!username || !password) return;

    // Check if user exists in registration requests or existing users
    const existingUser = findUserByUsername(username);
    
    if (!existingUser) {
      showToast('Invalid username or password', 'error');
      return;
    }

    // CRITICAL FIX: Validate password
    if (existingUser.password !== password) {
      showToast('Invalid username or password', 'error');
      return;
    }

    // Check if user is approved (for registration requests)
    if (existingUser.status === 'pending') {
      showToast('Your registration is pending approval. Please wait for admin approval.', 'info');
      return;
    }

    if (existingUser.status === 'rejected') {
      showToast('Your registration has been rejected. Please contact admin.', 'error');
      return;
    }

    // For simulation, login successful
    pushNavigationState();
    const linkedStudent = existingUser.role === "student" ? state.students.find(s => s.id === existingUser.studentId) : null;
    state.currentUser = { 
      name: existingUser.name, 
      role: existingUser.role, 
      studentId: linkedStudent ? linkedStudent.id : null,
      username: existingUser.username
    };
    state.currentView = "dashboard";
    saveState(); // Save state and update activity timestamp
    render();
  });

  document.getElementById("backToHomeBtn").addEventListener("click", () => {
    pushNavigationState();
    state.showLogin = false;
    render();
  });

  document.getElementById("toggleRegistration").addEventListener("click", (e) => {
    e.preventDefault();
    state.showRegistration = true;
    renderRegistration();
  });
}

function renderRegistration() {
  app.innerHTML = `
    <div class="auth-wrap">
      <div class="auth-card">
        <div class="hero">
          <div class="hero-brand-mark">
            <img class="hero-logo" src="${universityLogoSrc}" alt="Superior University logo" />
          </div>
          <h1>Registration Request</h1>
          <p>Submit your registration request for admin approval</p>
        </div>
        <form id="registrationForm" class="form">
          <div class="form-group">
            <label for="regName">Full Name:</label>
            <input type="text" id="regName" name="name" placeholder="Enter your full name" required />
            <div class="error" id="nameError"></div>
          </div>

          <div class="form-group">
            <label for="regUsername">Username:</label>
            <input type="text" id="regUsername" name="username" placeholder="Choose a username" required />
            <div class="error" id="usernameError"></div>
          </div>

          <div class="form-group">
            <label for="regEmail">Email Address:</label>
            <input type="email" id="regEmail" name="email" placeholder="Enter your email" required />
            <div class="error" id="emailError"></div>
          </div>

          <div class="form-group">
            <label for="regPassword">Password:</label>
            <input type="password" id="regPassword" name="password" placeholder="Enter a password" required />
            <div class="error" id="passwordError"></div>
          </div>

          <div class="form-group">
            <label for="regConfirmPassword">Confirm Password:</label>
            <input type="password" id="regConfirmPassword" name="confirmPassword" placeholder="Confirm your password" required />
            <div class="error" id="confirmPasswordError"></div>
          </div>

          <div class="form-group">
            <label for="regRole">Role:</label>
            <select id="regRole" name="role" required>
              <option value="">Select your role</option>
              <option value="student">Student</option>
              <option value="focal">Focal Person</option>
              <option value="accounts">Accounts Staff</option>
            </select>
            <div class="error" id="roleError"></div>
          </div>

          <div class="form-group" id="studentIdGroup" style="display: none;">
            <label for="regStudentId">Student ID (for students only):</label>
            <input type="text" id="regStudentId" name="studentId" placeholder="Enter your student ID" />
            <div class="error" id="studentIdError"></div>
          </div>

          <div class="form-actions">
            <button class="btn-primary" type="submit">Send Request</button>
            <button id="backToLoginBtn" class="btn-soft" type="button">Back to Login</button>
          </div>
        </form>
      </div>
    </div>
  `;

  // Show/hide student ID field based on role selection
  document.getElementById("regRole").addEventListener("change", (e) => {
    const studentIdGroup = document.getElementById("studentIdGroup");
    if (e.target.value === "student") {
      studentIdGroup.style.display = "block";
      document.getElementById("regStudentId").required = true;
    } else {
      studentIdGroup.style.display = "none";
      document.getElementById("regStudentId").required = false;
    }
  });

  document.getElementById("registrationForm").addEventListener("submit", (e) => {
    e.preventDefault();
    submitRegistrationRequest();
  });

  document.getElementById("backToLoginBtn").addEventListener("click", () => {
    state.showRegistration = false;
    render();
  });
}

function submitRegistrationRequest() {
  // Clear previous error messages
  document.getElementById("nameError").innerText = "";
  document.getElementById("usernameError").innerText = "";
  document.getElementById("emailError").innerText = "";
  document.getElementById("passwordError").innerText = "";
  document.getElementById("confirmPasswordError").innerText = "";
  document.getElementById("roleError").innerText = "";
  document.getElementById("studentIdError").innerText = "";

  // Get form values
  const name = document.getElementById("regName").value.trim();
  const username = document.getElementById("regUsername").value.trim();
  const email = document.getElementById("regEmail").value.trim();
  const password = document.getElementById("regPassword").value;
  const confirmPassword = document.getElementById("regConfirmPassword").value;
  const role = document.getElementById("regRole").value;
  const studentId = document.getElementById("regStudentId").value.trim();

  let isValid = true;

  // Name validation
  if (name === "") {
    document.getElementById("nameError").innerText = "Please enter your name";
    isValid = false;
  }

  // Username validation
  if (username === "") {
    document.getElementById("usernameError").innerText = "Please enter a username";
    isValid = false;
  } else if (findUserByUsername(username)) {
    document.getElementById("usernameError").innerText = "Username already exists";
    isValid = false;
  }

  // Email validation
  if (email === "") {
    document.getElementById("emailError").innerText = "Please enter your email";
    isValid = false;
  } else if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    document.getElementById("emailError").innerText = "Please enter a valid email";
    isValid = false;
  }

  // Password validation
  if (password === "") {
    document.getElementById("passwordError").innerText = "Please enter a password";
    isValid = false;
  } else if (password.length < 6) {
    document.getElementById("passwordError").innerText = "Password must be at least 6 characters";
    isValid = false;
  }

  // Confirm Password validation
  if (confirmPassword !== password) {
    document.getElementById("confirmPasswordError").innerText = "Passwords do not match";
    isValid = false;
  }

  // Role validation
  if (role === "") {
    document.getElementById("roleError").innerText = "Please select your role";
    isValid = false;
  }

  // Student ID validation (only for students)
  if (role === "student" && studentId === "") {
    document.getElementById("studentIdError").innerText = "Please enter your student ID";
    isValid = false;
  }

  if (isValid) {
    // Create registration request
    const request = {
      id: generateRequestId(),
      username: username,
      password: password, // In production, this should be hashed
      name: name,
      email: email,
      role: role,
      studentId: role === "student" ? studentId : null,
      status: "pending",
      createdAt: new Date().toISOString().split('T')[0]
    };

    // Add to registration requests
    state.registrationRequests.push(request);
    saveState();

    // Show success message
    showToast('Registration request submitted successfully! Please wait for admin approval.', 'success');

    // Reset form and go back to login
    document.getElementById("registrationForm").reset();
    setTimeout(() => {
      state.showRegistration = false;
      render();
    }, 2000);
  }

  return false;
}

function validateRegistrationForm() {
  // Clear previous error messages
  document.getElementById("nameError").innerText = "";
  document.getElementById("emailError").innerText = "";
  document.getElementById("passwordError").innerText = "";
  document.getElementById("confirmPasswordError").innerText = "";
  document.getElementById("ageError").innerText = "";
  document.getElementById("genderError").innerText = "";
  document.getElementById("addressError").innerText = "";

  // Get form values
  const name = document.getElementById("regName").value.trim();
  const email = document.getElementById("regEmail").value.trim();
  const password = document.getElementById("regPassword").value;
  const confirmPassword = document.getElementById("regConfirmPassword").value;
  const age = document.getElementById("regAge").value;
  const gender = document.querySelector('input[name="gender"]:checked');
  const address = document.getElementById("regAddress").value.trim();

  let isValid = true;

  // Name validation
  if (name === "") {
    document.getElementById("nameError").innerText = "Please enter your name";
    isValid = false;
  }

  // Email validation
  if (email === "") {
    document.getElementById("emailError").innerText = "Please enter your email";
    isValid = false;
  } else if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    document.getElementById("emailError").innerText = "Please enter a valid email";
    isValid = false;
  }

  // Password validation
  if (password === "") {
    document.getElementById("passwordError").innerText = "Please enter a password";
    isValid = false;
  } else if (password.length < 6) {
    document.getElementById("passwordError").innerText = "Password must be at least 6 characters";
    isValid = false;
  }

  // Confirm Password validation
  if (confirmPassword !== password) {
    document.getElementById("confirmPasswordError").innerText = "Passwords do not match";
    isValid = false;
  }

  // Age validation
  if (age === "") {
    document.getElementById("ageError").innerText = "Please enter your age";
    isValid = false;
  } else if (age < 1 || age > 100) {
    document.getElementById("ageError").innerText = "Age must be between 1 and 100";
    isValid = false;
  }

  // Gender validation
  if (!gender) {
    document.getElementById("genderError").innerText = "Please select your gender";
    isValid = false;
  }

  // Address validation
  if (address === "") {
    document.getElementById("addressError").innerText = "Please enter your address";
    isValid = false;
  }

  
  if (isValid) {
    // Reset form
    document.getElementById("registrationForm").reset();
    setTimeout(() => {
      document.getElementById("registrationBackdrop").classList.add("hidden");
      state.showRegistration = false;
      render();
    }, 2000);
  }

  return false;
}

function bindLandingActions() {
  const loginBtn = document.getElementById("openLoginBtn");
  if (loginBtn) {
    loginBtn.addEventListener("click", () => {
      pushNavigationState();
      state.showLogin = true;
      state.loginRolePreset = "student";
      render();
    });
  }

  document.querySelectorAll(".faq-question").forEach((q) => {
    q.addEventListener("click", () => {
      const item = q.closest(".faq-item");
      const isActive = item.classList.contains("active");
      
      // Close all other items
      document.querySelectorAll(".faq-item").forEach((i) => i.classList.remove("active"));
      
      if (!isActive) {
        item.classList.add("active");
      }
    });
  });

  document.querySelectorAll("[data-open-role]").forEach((btn) => {
    btn.addEventListener("click", () => {
      pushNavigationState();
      state.showLogin = true;
      state.loginRolePreset = btn.getAttribute("data-open-role");
      render();
    });
  });

  // Route preview buttons
  document.querySelectorAll(".route-preview-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const routeId = btn.getAttribute("data-route-preview");
      const route = state.routes.find(r => r.id === routeId);
      if (route) {
        const stopsHtml = route.stops.map((stop, index) => 
          `<li><strong>${index + 1}.</strong> ${stop}</li>`
        ).join("");
        openModal(
          `Route ${route.bookNo} · ${route.name} - All Stops`,
          `<div class="route-details">
            <p><strong>Route:</strong> ${route.catalogTitle}</p>
            <p><strong>Yearly Fare:</strong> ${route.yearlyFare}</p>
            <p><strong>Total Stops:</strong> ${route.stops.length}</p>
            <h4>Complete Stop List:</h4>
            <ol class="route-stops-list">
              ${stopsHtml}
            </ol>
          </div>`
        );
      }
    });
  });
}

function renderShell() {
  const { role, name } = state.currentUser || { role: 'guest' };
  const activeView = state.currentView;
  const menu = roleMenus[role] || [];
  const page = renderPageByRole(role, activeView);

  // If shell exists and role hasn't changed, only update content
  const existingShell = document.querySelector('.app-shell');
  if (existingShell && app.dataset.currentRole === role) {
    const mainContent = document.getElementById('viewContent');
    const titleEl = document.querySelector('.topbar .title');
    
    if (titleEl) titleEl.textContent = page.title;
    if (mainContent) mainContent.innerHTML = page.content;
    
    // Update active menu item
    document.querySelectorAll('.sidebar .menu button').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.view === activeView);
    });

    bindPageActions();
    return;
  }

  // Otherwise, full shell render (Role change or first load)
  app.dataset.currentRole = role;
  app.innerHTML = `
    <div class="app-shell">
      <aside class="sidebar">
        <div class="brand">
          <div class="brand-head">
            <img class="sidebar-logo" src="${universityLogoSrc}" alt="Superior University logo" />
            <h2>UTAP</h2>
          </div>
          <p class="role-pill role-${role}">${roleLabel(role)}</p>
        </div>
        <nav class="menu">
          ${menu.map((m) => `<button class="${m.id === activeView ? "active" : ""}" data-view="${m.id}">${m.label}</button>`).join("")}
        </nav>
      </aside>
      <main class="main">
        <div class="topbar">
          <h1 class="title">${page.title}</h1>
          <div class="inline-actions">
            <div class="notif-bell-wrap ${state.unreadNotifCount > 0 ? 'has-unread' : ''}" id="topNavNotifBell" data-view="notifications">
               <i class="fas fa-bell"></i>
               ${state.unreadNotifCount > 0 ? `<span class="notif-badge">${state.unreadNotifCount}</span>` : ""}
            </div>
            <button id="showProfileBtn" class="btn-soft">Profile</button>
            <button id="logoutBtn" class="btn-primary">Logout</button>
          </div>
        </div>
        <div id="viewContent">
          ${page.content}
        </div>
        <div id="toastContainer" class="toast-container"></div>
      </main>
    </div>
  `;

  // Persistent event listeners
  document.querySelectorAll("[data-view]").forEach((btn) => {
    btn.onclick = () => {
      pushNavigationState();
      state.currentView = btn.dataset.view;
      state.searchQuery = ""; // Clear global search when switching views
      render();
    };
  });

  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
    logoutBtn.onclick = () => {
      pushNavigationState();
      state.currentUser = null;
      state.currentView = "home";
      forwardStack = []; // Clear forward stack on logout
      render();
    };
  }

  const profileBtn = document.getElementById("showProfileBtn");
  if (profileBtn) {
    profileBtn.onclick = () => {
      openModal(
        "User Profile",
        `<p><strong>Name:</strong> ${state.currentUser.name}</p><p><strong>Role:</strong> ${roleLabel(state.currentUser.role)}</p><p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>`
      );
    }
  }

  bindPageActions();
}

function getFocalPersonRoute() {
  if (!state.currentUser || state.currentUser.role !== "focal") return null;
  
  // Find the focal person entry for the current user
  const focalPerson = state.focalPersons.find(fp => 
    fp.name.toLowerCase().includes(state.currentUser.name.toLowerCase())
  );
  
  return focalPerson ? focalPerson.route : null;
}

function getAssignedBusesForFocal() {
  const focalRoute = getFocalPersonRoute();
  if (!focalRoute) return [];
  
  return state.buses.filter(bus => bus.route === focalRoute);
}

function dashboardCards(role) {
  const totalStudents = state.students.length;
  const totalRoutes = state.routes.length;
  const unpaid = state.students.filter((s) => s.feeStatus === "Unpaid").length;
  const present = state.attendance.filter((a) => a.status === "Present").length;

  let common = '';
  
  if (role === "focal") {
    // For focal persons, show total buses and assigned buses separately
    const assignedBuses = getAssignedBusesForFocal();
    const totalBuses = state.buses.length;
    
    common = `
      <div class="card span-4"><div class="stats"><span class="label">Total Students</span><span class="value">${totalStudents}</span></div></div>
      <div class="card span-4"><div class="stats"><span class="label">Total Routes</span><span class="value">${totalRoutes}</span></div></div>
      <div class="card span-4"><div class="stats"><span class="label">Total Buses</span><span class="value">${totalBuses}</span></div></div>
      <div class="card span-6"><div class="stats focal-assigned"><span class="label">Assigned to You</span><span class="value">${assignedBuses.length}</span></div></div>
      <div class="card span-6"><div class="stats focal-total"><span class="label">System Total</span><span class="value">${totalBuses}</span></div></div>
    `;
  } else {
    // For other roles, show standard stats
    common = `
      <div class="card span-4"><div class="stats"><span class="label">Total Students</span><span class="value">${totalStudents}</span></div></div>
      <div class="card span-4"><div class="stats"><span class="label">Total Routes</span><span class="value">${totalRoutes}</span></div></div>
      <div class="card span-4"><div class="stats"><span class="label">Total Buses</span><span class="value">${state.buses.length}</span></div></div>
    `;
  }

  if (role === "student") {
    const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
    const bus = busByRoute(student.route);
    return `
      <div class="grid">
        <div class="card span-6"><div class="stats"><span class="label">Assigned Route</span><span class="value">${studentRouteName(student.route)}</span></div></div>
        <div class="card span-6"><div class="stats"><span class="label">Assigned Bus</span><span class="value">${bus ? bus.id : "Not Assigned"}</span></div></div>
        <div class="card span-12"><p><strong>Fee Status:</strong> ${renderStatus(student.feeStatus)}</p></div>
      </div>

      <div class="grid">
        <div class="card span-12">
          <h3>Student Dashboard</h3>
          <p>Welcome to your dashboard. Access your attendance records, fee status, and other student services.</p>
                  </div>
      </div>
    `;
  }

  if (role === "focal") {
    const unpaidStudents = state.students.filter((s) => s.feeStatus === "Unpaid");

    return `
      <div class="grid">
        ${common}
        <div class="card span-6"><div class="stats"><span class="label">Today's Present</span><span class="value">${present}</span></div></div>
        <div class="card span-6"><div class="stats"><span class="label">Unpaid Students</span><span class="value ${unpaid > 0 ? "alert-unpaid" : ""}">${unpaid}</span></div></div>
      </div>

      <div class="grid">
        <div class="card span-12">
          <h3>Focal Person Dashboard</h3>
          <p>Welcome to your dashboard. Use the navigation menu to access attendance marking, fee reminders, and other features.</p>
          
          ${(() => {
            const assignedBuses = getAssignedBusesForFocal();
            const focalRoute = getFocalPersonRoute();
            
            if (assignedBuses.length > 0) {
              return `
                <div class="assigned-buses-section">
                  <h4><i class="fas fa-star"></i> Your Assigned Buses - Exclusive Access</h4>
                  <p class="section-description">These buses are exclusively assigned to your route and under your supervision:</p>
                  <div class="assigned-buses-grid">
                    ${assignedBuses.map(bus => `
                      <div class="assigned-bus-card focal-exclusive">
                        <div class="bus-badge">
                          <i class="fas fa-crown"></i>
                          <span>ASSIGNED</span>
                        </div>
                        <div class="bus-id">${bus.id}</div>
                        <div class="bus-details">
                          <div class="bus-model">${bus.model}</div>
                          <div class="bus-capacity">Capacity: ${bus.capacity} students</div>
                          <div class="bus-route">Route: ${focalRoute}</div>
                        </div>
                        <div class="bus-actions">
                          <button class="btn-soft btn-small" onclick="viewBusDetails('${bus.id}')">
                            <i class="fas fa-eye"></i> Details
                          </button>
                        </div>
                      </div>
                    `).join('')}
                  </div>
                </div>
              `;
            } else {
              return `
                <div class="assigned-buses-section no-assignment">
                  <h4><i class="fas fa-exclamation-triangle"></i> No Buses Assigned</h4>
                  <p class="no-buses-message">You currently have no buses assigned to your route. Please contact the transport administrator to get buses assigned to your supervision.</p>
                </div>
              `;
            }
          })()}
          
                  </div>
      </div>
    `;
  }

  if (role === "accounts") {
    const collected = state.students.filter((s) => s.feeStatus === "Paid").reduce((a, b) => a + Number(b.feeAmount), 0);
    return `
      <div class="grid">
        ${common}
        <div class="card span-6"><div class="stats"><span class="label">Fees Collected (PKR)</span><span class="value">${collected.toLocaleString()}</span></div></div>
        <div class="card span-6"><div class="stats"><span class="label">Overdue Students</span><span class="value ${unpaid > 0 ? "alert-unpaid" : ""}">${unpaid}</span></div></div>
      </div>

      <div class="grid">
        <div class="card span-12">
          <h3>Accounts Office Dashboard</h3>
          <p>Manage fee collections, process payments, and generate financial reports.</p>
                  </div>
      </div>
    `;
  }

  // admin
  const focalCount = state.focalPersons.length;
  return `
    <div class="grid">
      ${common}
      <div class="card span-6"><div class="stats"><span class="label">Focal Persons</span><span class="value">${focalCount}</span></div></div>
      <div class="card span-6"><div class="stats"><span class="label">Unpaid Students</span><span class="value ${unpaid > 0 ? "alert-unpaid" : ""}">${unpaid}</span></div></div>
    </div>

    <div class="grid">
      <div class="card span-12">
        <h3>Admin Dashboard</h3>
        <p>Manage the entire transport system, oversee operations, and generate comprehensive reports.</p>
              </div>
    </div>
  `;
}

function renderStatus(status) {
  if (status === "Paid" || status === "Present") {
    return `<span class="badge badge-ok">${status}</span>`;
  }
  return `<span class="badge badge-bad">${status}</span>`;
}

function addPDFHeader(doc, title) {
  // Add University Logo (from DOM)
  const logoImg = document.querySelector('.sidebar-logo') || document.querySelector('.brand-logo');
  if (logoImg) {
    try {
      doc.addImage(logoImg, 'JPEG', 15, 12, 20, 20);
    } catch (e) {}
  }

  doc.setFontSize(22);
  doc.setTextColor(62, 13, 72); // Purple color
  doc.setFont('helvetica', 'bold');
  doc.text("University Transport Automation Portal", 40, 20);
  
  doc.setFontSize(14);
  doc.setTextColor(100, 100, 100);
  doc.setFont('helvetica', 'normal');
  doc.text(title, 40, 28);
  
  doc.setDrawColor(62, 13, 72);
  doc.setLineWidth(0.8);
  doc.line(15, 35, 195, 35);
  
  doc.setFontSize(9);
  doc.setTextColor(150, 150, 150);
  doc.text(`Official System Report · Issued: ${new Date().toLocaleString()}`, 195, 42, { align: "right" });
  
  return 55; // Return the next Y position
}

function studentFeesTable() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const studentInstallments = state.feeInstallments.filter((f) => f.studentId === student.id);
  
  // Calculate fee statistics
  const totalAmount = studentInstallments.reduce((sum, inst) => sum + inst.amount, 0);
  const paidAmount = studentInstallments.filter((inst) => inst.status === "Paid").reduce((sum, inst) => sum + inst.amount, 0);
  const unpaidAmount = studentInstallments.filter((inst) => inst.status === "Unpaid").reduce((sum, inst) => sum + inst.amount, 0);
  const pendingAmount = studentInstallments.filter((inst) => inst.status === "Pending").reduce((sum, inst) => sum + inst.amount, 0);
  
  // Sort installments by due date
  const sortedInstallments = [...studentInstallments].sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
  
  return `
    <div class="fee-status-container">
      <div class="fee-summary-cards">
        <div class="card">
          <h3>Fee Summary</h3>
          <div class="summary-stats">
            <div class="stat-item">
              <span class="stat-value">PKR ${totalAmount.toLocaleString()}</span>
              <span class="stat-label">Total Annual Fee</span>
            </div>
            <div class="stat-item">
              <span class="stat-value paid">PKR ${paidAmount.toLocaleString()}</span>
              <span class="stat-label">Paid Amount</span>
            </div>
            <div class="stat-item">
              <span class="stat-value unpaid">PKR ${unpaidAmount.toLocaleString()}</span>
              <span class="stat-label">Overdue Amount</span>
            </div>
            <div class="stat-item">
              <span class="stat-value pending">PKR ${pendingAmount.toLocaleString()}</span>
              <span class="stat-label">Pending Amount</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <h3>Installment Details</h3>
          <button class="btn-primary" onclick="downloadFeeStatusPDF()">
            <i class="fas fa-download"></i> Download PDF
          </button>
        </div>
        
        <div class="student-info">
          <p><strong>Student ID:</strong> ${student.id}</p>
          <p><strong>Name:</strong> ${student.name}</p>
          <p><strong>Route:</strong> ${studentRouteName(student.route)}</p>
        </div>
        
        <div class="table-container">
          <table class="fee-table">
            <thead>
              <tr>
                <th>Installment #</th>
                <th>Amount (PKR)</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Payment Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              ${sortedInstallments.map((installment) => {
                const statusClass = installment.status === "Paid" ? "paid" : 
                                   installment.status === "Unpaid" ? "unpaid" : "pending";
                const isOverdue = installment.status === "Unpaid" && new Date(installment.dueDate) < new Date();
                
                return `
                  <tr class="${isOverdue ? 'overdue' : ''}">
                    <td>${installment.installment}</td>
                    <td>PKR ${installment.amount.toLocaleString()}</td>
                    <td>${installment.dueDate}</td>
                    <td class="status-${statusClass}">
                      ${installment.status}
                      ${isOverdue ? '<span class="overdue-badge">Overdue</span>' : ''}
                    </td>
                    <td>${installment.paymentDate}</td>
                    <td>
                      ${installment.status === "Paid" ? 
                        '<span class="badge badge-good">Paid</span>' : 
                        `<button class="btn-soft" onclick="payInstallment('${installment.studentId}', ${installment.installment})">
                          ---
                        </button>`
                      }
                    </td>
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

function downloadFeeStatusPDF() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const studentInstallments = state.feeInstallments.filter((f) => f.studentId === student.id);
  const sortedInstallments = [...studentInstallments].sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
  
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'Student Fee Status Report');
  
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text(`Student Name: ${student.name}`, 20, startY);
  doc.text(`Student ID: ${student.id}`, 20, startY + 7);
  doc.text(`Route: ${studentRouteName(student.route)}`, 20, startY + 14);
  
  const tableData = sortedInstallments.map(inst => [
    `#${inst.installment}`,
    `PKR ${inst.amount.toLocaleString()}`,
    inst.dueDate,
    inst.status,
    inst.paymentDate
  ]);
  
  doc.autoTable({
    startY: startY + 25,
    head: [['Installment', 'Amount', 'Due Date', 'Status', 'Payment Date']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [62, 13, 72], textColor: [255, 255, 255], fontStyle: 'bold' },
    alternateRowStyles: { fillColor: [248, 240, 255] }
  });
  
  doc.save(`fee_status_${student.id}_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Fee Status PDF downloaded', 'success');
}

function payInstallment(studentId, installmentNumber) {
  alert(`Payment processing for Student ${studentId}, Installment #${installmentNumber}. This would integrate with payment gateway.`);
}

function renderPageByRole(role, view) {
  const page = { title: "Dashboard", content: "" };

  if (view === "dashboard") {
    page.title = "Dashboard";
    page.content = dashboardCards(role);
    return page;
  }

  if (role === "student" && view === "register") {
    page.title = "Chnage Route";
    page.content = `
      <div class="grid">
        <div class="card span-8">
          <form id="studentRegisterForm" class="form">
            <div><label>Name</label><input id="regName" value="${state.currentUser.name}" required /></div>
            <div><label>Student ID</label><input id="regId" value="${state.currentUser.studentId || "ST-NEW"}" required /></div>
            <div>
              <label>Select Route</label>
              <select id="regRoute">
                ${state.routes.map((r) => `<option value="${r.id}">${r.id} - ${r.name}</option>`).join("")}
              </select>
            </div>
            <button class="btn-primary" type="submit">Send Request</button>
          </form>
        </div>
        <div class="card span-4">
          <h3>Route Info (Faisalabad)</h3>
          ${state.routes.map((r) => `<p><strong>${r.id}</strong>: ${r.name}</p>`).join("")}
        </div>
      </div>
    `;
    return page;
  }

  if (role === "student" && view === "attendance") {
    page.title = "Attendance Record";
    page.content = renderStudentAttendanceView();
    return page;
  }

  

  if (role === "student" && view === "feedback") {
    page.title = "Transport Feedback";
    page.content = renderFeedbackView();
    return page;
  }

  if ((role === "student" || role === "focal" || role === "admin" || role === "accounts") && view === "notifications") {
    page.title = "Notifications";
    page.content = renderNotificationsView(role);
    return page;
  }


  if (role === "admin" && view === "admin_dashboard") {
    page.title = "Admin Control Center";
    page.content = renderAdminDashboard();
    return page;
  }

  if (role === "admin" && view === "requests") {
    page.title = "Registration Requests";
    page.content = renderRegistrationRequests();
    return page;
  }

  if ((role === "admin" || role === "focal") && view === "send_notif") {
    page.title = "Send Notification";
    page.content = renderSendNotificationView();
    return page;
  }

  if (role === "student" && view === "fees") {
    page.title = "Fee Status / Payment";
    page.content = `<div class="grid">${studentFeesTable()}</div>`;
    return page;
  }

  if (role === "focal" && (view === "attendance" || view === "mark_attendance")) {
    const q = (state.searchQuery || "").toLowerCase();
    const filteredStudents = state.students.filter(s => 
      s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q)
    );
    
    page.title = "Attendance Marking";
    page.content = `
      <div class="grid">
        <div class="card span-12">
          <div class="card-header">
            <h3>Attendance Control</h3>
            <button class="btn-primary" onclick="downloadAttendanceHistoryPDF()">
              <i class="fas fa-file-pdf"></i> History PDF
            </button>
          </div>
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" id="mainSearchInput" class="search-input" placeholder="Quick Search Student Name or ID..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
          </div>
          <table id="attendanceTable">
            <thead>
              <tr>
                <th>Registration #</th>
                <th>Full Name</th>
                <th>Current Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              ${filteredStudents.map((s) => {
                const today = new Date().toISOString().split('T')[0];
                const att = state.attendance.find((a) => a.studentId === s.id && a.date === today) || { date: today, status: "Pending" };
                const statusClass = att.status === "Present" ? "status-present" : att.status === "Absent" ? "status-absent" : "status-pending";
                return `
                  <tr data-student-id="${s.id}">
                    <td>${s.id}</td>
                    <td>${s.name}</td>
                    <td class="${statusClass}" data-status-cell="true">${att.status}</td>
                    <td>
                      <button class="btn-att-present" data-attendance-id="${s.id}" data-attendance-status="Present">✓ Present</button>
                      <button class="btn-att-absent" data-attendance-id="${s.id}" data-attendance-status="Absent">✗ Absent</button>
                    </td>
                  </tr>
                `;
              }).join("") || '<tr><td colspan="4" class="p-1">No matching students found.</td></tr>'}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page;
  }

  if (role === "focal" && view === "fee_reminders") {
    const unpaidStudents = state.students.filter((s) => s.feeStatus === "Unpaid");
    page.title = "Fee Reminders";
    page.content = renderFeeRemindersView(unpaidStudents);
    return page;
  }

  if (role === "focal" && view === "unpaid") {
    const q = (state.searchQuery || "").toLowerCase();
    const unpaidStudents = state.students.filter((s) => 
      s.feeStatus === "Unpaid" && (s.name.toLowerCase().includes(q) || s.id.toLowerCase().includes(q))
    );
    page.title = "Unpaid Students";
    page.content = `
      <div class="grid">
        <div class="card span-12">
          <div class="card-header">
            <h3>Fee Delinquency Report</h3>
            <button class="btn-primary" onclick="downloadUnpaidStudentsPDF()">
              <i class="fas fa-file-download"></i> Export PDF
            </button>
          </div>
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" id="mainSearchInput" class="search-input" placeholder="Search Unpaid Students..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
          </div>
          <table>
            <thead><tr><th>Student</th><th>Route</th><th>Fee</th><th>Overdue Days</th><th>Status</th></tr></thead>
            <tbody>
              ${unpaidStudents.map((s) => {
                const overdueDays = Math.floor((new Date() - new Date(s.lastPaymentDate)) / (1000 * 60 * 60 * 24));
                return `
                  <tr>
                    <td>${s.name} (${s.id})</td>
                    <td>${studentRouteName(s.route)}</td>
                    <td>PKR ${s.feeAmount}</td>
                    <td class="${overdueDays > 30 ? 'overdue' : ''}">${overdueDays} days</td>
                    <td>${renderStatus(s.feeStatus)}</td>
                    <td><button class="btn-soft" onclick="sendReminderToStudent('${s.id}', '${s.name}')">
                      
                    </button></td>
                  </tr>
                `;
              }).join("") || `<tr><td colspan="6">No unpaid students.</td></tr>`}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page; 
  }

  if (role === "accounts" && view === "payments") {
    const q = (state.searchQuery || "").trim().toLowerCase();
    const filteredStudents = state.students.filter(s => {
      const route = studentRouteName(s.route).toLowerCase();
      return (s.name || '').toLowerCase().includes(q) || 
             (s.id || '').toLowerCase().includes(q) || 
             route.includes(q);
    });
    
    page.title = "Process Payments";
    page.content = `
      <div class="grid">
        <div class="card span-12">
          <div class="card-header">
            <h3>Collect Student Fees</h3>
            <div class="payment-actions">
              <button class="btn-primary" onclick="downloadPaymentsPDF()">
                <i class="fas fa-file-pdf"></i> PDF
              </button>
              <button class="btn-soft" onclick="downloadPaymentsExcel()">
                <i class="fas fa-file-excel"></i> Excel
              </button>
            </div>
          </div>
          
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" 
                   id="mainSearchInput"
                   class="search-input" 
                   placeholder="Search by student name, ID or route..." 
                   value="${state.searchQuery || ''}"
                   oninput="handleSearch(this.value)">
          </div>
          
          <table class="payment-table">
            <thead><tr><th>ID</th><th>Student Name</th><th>Current Route</th><th>Amount (PKR)</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
              ${filteredStudents.map((s, index) => `
                <tr data-student-id="${s.id}">
                  <td>${s.id}</td>
                  <td>${s.name}</td>
                  <td>${studentRouteName(s.route)}</td>
                  <td class="amount-cell">${Number(s.feeAmount).toLocaleString()}</td>
                  <td class="status-cell">
                    <span class="status-badge ${s.feeStatus === 'Paid' ? 'paid' : 'unpaid'}">
                      ${s.feeStatus}
                    </span>
                  </td>
                  <td class="actions-cell">
                    <div class="action-buttons">
                      ${s.feeStatus === 'Unpaid' ? `
                        <button class="btn-receive" onclick="receivePayment('${s.id}')" title="Receive Payment">
                          <i class="fas fa-check-circle"></i> Receive
                        </button>
                      ` : `
                        <button class="btn-undo" onclick="undoPayment('${s.id}')" title="Undo Payment">
                          <i class="fas fa-undo"></i> Undo
                        </button>
                      `}
                      <button class="btn-details" onclick="showPaymentDetails('${s.id}')" title="View Details">
                        <i class="fas fa-info-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              `).join("") || '<tr><td colspan="6">No results found for "'+state.searchQuery+'"</td></tr>'}
            </tbody>
          </table>
          
          <div class="payment-summary">
            <div class="summary-stats">
              <div class="stat-item">
                <span class="stat-label">Total Students:</span>
                <span class="stat-value">${filteredStudents.length}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Paid:</span>
                <span class="stat-value paid">${filteredStudents.filter(s => s.feeStatus === 'Paid').length}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Unpaid:</span>
                <span class="stat-value unpaid">${filteredStudents.filter(s => s.feeStatus === 'Unpaid').length}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Total Collected:</span>
                <span class="stat-value amount">${filteredStudents.filter(s => s.feeStatus === 'Paid').reduce((sum, s) => sum + Number(s.feeAmount), 0).toLocaleString()} PKR</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    return page;
  }

  if ((role === "accounts" || role === "admin") && view === "reports") {
    page.title = role === "accounts" ? "Payment Reports" : "System Reports";
    const q = (state.searchQuery || "").toLowerCase();
    const filteredStudents = state.students.filter((s) => {
      const routeName = studentRouteName(s.route).toLowerCase();
      const att = state.attendance.find((a) => a.studentId === s.id) || { date: "-", status: "-" };
      return (
        s.id.toLowerCase().includes(q) ||
        s.name.toLowerCase().includes(q) ||
        routeName.includes(q) ||
        s.feeStatus.toLowerCase().includes(q) ||
        att.status.toLowerCase().includes(q)
      );
    });
    const routeStudents = filteredStudents.map((s) => ({
      ...s,
      attendance: state.attendance.find((a) => a.studentId === s.id) || { date: "-", status: "-" }
    }));
    page.content = `
      <div class="grid">
        <div class="card span-12">
          <h3>Download Reports</h3>
          <p class="pass-subtle">Export the current student list and status for offline analysis.</p>
          <div class="export-actions">
            <button id="downloadCsvBtn" class="btn-soft">Download CSV Report</button>
          </div>
        </div>
        <div class="card span-12">
          <div class="inline-actions">
            <div class="search-container w-full">
              <div class="search-icon-box"><i class="fas fa-search"></i></div>
              <input type="text" id="mainSearchInput" class="search-input" placeholder="Search reports by student, route, or status..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
            </div>
          </div>
          <p class="pass-subtle">Displaying ${filteredStudents.length} of ${state.students.length} students.</p>
        </div>
        <div class="card span-4"><div class="stats"><span class="label">Total Paid Entries</span><span class="value">${state.students.filter(s => s.feeStatus === 'Paid').length}</span></div></div>
        <div class="card span-4"><div class="stats"><span class="label">Total Unpaid Entries</span><span class="value alert-unpaid">${state.students.filter(s => s.feeStatus === 'Unpaid').length}</span></div></div>
        <div class="card span-4"><div class="stats"><span class="label">Consolidated Revenue</span><span class="value">${state.students.filter(s => s.feeStatus === 'Paid').reduce((a, b) => a + Number(b.feeAmount), 0).toLocaleString()} PKR</span></div></div>
        <div class="card span-12">
          <button id="sortByAmountBtn" class="btn-soft">Sort Table by Fee Amount</button>
          <table>
            <thead><tr><th>Student</th><th>Route</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
              ${routeStudents
                .sort((a, b) => state.sort.order === "asc" ? a.feeAmount - b.feeAmount : b.feeAmount - a.feeAmount)
                .map((s) => `
                <tr>
                  <td>${s.name}</td>
                  <td>${studentRouteName(s.route)}</td>
                  <td>${s.feeAmount}</td>
                  <td>${renderStatus(s.feeStatus)}</td>
                </tr>
              `).join("")}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page;
  }

  if (role === "admin" && view === "complaints") {
    page.title = "Complaints Management";
    page.content = renderAdminComplaints();
    return page;
  }

  if (role === "admin" && view === "buses") {
    page.title = "Bus Management";
    page.content = `
      <div class="grid">
        <div class="card span-6">
          <h3>Add / Edit Bus</h3>
          <form id="busForm" class="form">
            <div><label>Bus ID</label><input id="busId" placeholder="BUS-20" required /></div>
            <div><label>Model</label><input id="busModel" placeholder="Hino 2021" required /></div>
            <div><label>Capacity</label><input id="busCapacity" type="number" min="20" required /></div>
            <div>
              <label>Assigned Route</label>
              <select id="busRoute">${state.routes.map((r) => `<option value="${r.id}">${r.id}</option>`).join("")}</select>
            </div>
            <button class="btn-primary" type="submit">Save Bus</button>
          </form>
        </div>
        <div class="card span-6">
          <div class="card-header">
            <h3>Bus Management</h3>
            <div class="payment-actions">
              <button class="btn-primary" onclick="downloadBusesPDF()">PDF</button>
              <button class="btn-soft" onclick="downloadBusesCSV()">Excel</button>
            </div>
          </div>
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" id="mainSearchInput" class="search-input" placeholder="Search by bus ID, model or route..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
          </div>
          <table>
            <thead><tr><th>ID</th><th>Model</th><th>Cap.</th><th>Route</th><th>Action</th></tr></thead>
            <tbody>
              ${state.buses.filter(b => 
                (b.id || '').toLowerCase().includes((state.searchQuery || '').toLowerCase()) ||
                (b.model || '').toLowerCase().includes((state.searchQuery || '').toLowerCase()) ||
                (b.route || '').toLowerCase().includes((state.searchQuery || '').toLowerCase())
              ).map((b) => `
                <tr>
                  <td>${b.id}</td>
                  <td>${b.model}</td>
                  <td>${b.capacity}</td>
                  <td>${b.route}</td>
                  <td class="inline-actions">
                    <button class="icon-btn" data-bus-view="${b.id}">View</button>
                    <button class="icon-btn" data-bus-delete="${b.id}">Delete</button>
                  </td>
                </tr>
              `).join("") || '<tr><td colspan="5">No buses found.</td></tr>'}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page;
  }

  if (role === "admin" && view === "routes") {
    page.title = "Route Management";
    page.content = `
      <div class="grid">
        <div class="card span-6">
          <h3>Add / Edit Route</h3>
          <form id="routeForm" class="form">
            <div><label>Route ID</label><input id="routeId" placeholder="R-09" required /></div>
            <div><label>Route Name</label><input id="routeName" placeholder="New Faisalabad Route" required /></div>
            <div><label>Stops (comma separated)</label><textarea id="routeStops" rows="3" placeholder="Stop A, Stop B, Stop C"></textarea></div>
            <button class="btn-primary" type="submit">Save Route</button>
          </form>
        </div>
        <div class="card span-6">
          <div class="card-header">
            <h3>Available Routes</h3>
            <div class="payment-actions">
               <button class="btn-primary" onclick="downloadRoutesPDF()">PDF</button>
               <button class="btn-soft" onclick="downloadRoutesCSV()">Excel</button>
            </div>
          </div>
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" id="mainSearchInput" class="search-input" placeholder="Search routes by ID or name..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
          </div>
          <table>
            <thead><tr><th>ID</th><th>Name</th><th>Stops</th><th>Action</th></tr></thead>
            <tbody>
              ${state.routes.filter(r => 
                (r.id || '').toLowerCase().includes((state.searchQuery || '').toLowerCase()) ||
                (r.name || '').toLowerCase().includes((state.searchQuery || '').toLowerCase())
              ).map((r) => `
                <tr>
                  <td>${r.id}</td>
                  <td>${r.name}</td>
                  <td>${r.stops.length}</td>
                  <td class="inline-actions">
                    <button class="icon-btn" data-route-view="${r.id}">View</button>
                    <button class="icon-btn" data-route-delete="${r.id}">Delete</button>
                  </td>
                </tr>
              `).join("") || '<tr><td colspan="4">No routes found.</td></tr>'}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page;
  }

  if (role === "admin" && view === "focals") {
    page.title = "Focal Person Management";
    page.content = `
      <div class="grid">
        <div class="card span-6">
          <h3>Assign Focal Person</h3>
          <form id="focalForm" class="form">
            <div><label>Focal ID</label><input id="focalId" placeholder="FP-07" required /></div>
            <div><label>Name</label><input id="focalName" placeholder="Mr/Ms Name" required /></div>
            <div><label>Assigned Route</label><select id="focalRoute">${state.routes.map((r) => `<option value="${r.id}">${r.id} - ${r.name}</option>`).join("")}</select></div>
            <button class="btn-primary" type="submit">Save Focal Person</button>
          </form>
        </div>
        <div class="card span-6">
          <div class="card-header">
            <h3>Focal List</h3>
            <div class="payment-actions">
               <button class="btn-primary" onclick="downloadFocalListPDF()">PDF</button>
               <button class="btn-soft" onclick="downloadFocalListCSV()">Excel</button>
            </div>
          </div>
          <div class="search-container mt-2">
            <div class="search-icon-box"><i class="fas fa-search"></i></div>
            <input type="text" id="mainSearchInput" class="search-input" placeholder="Search focal name or route..." value="${state.searchQuery || ''}" oninput="handleSearch(this.value)">
          </div>
          <table>
            <thead><tr><th>ID</th><th>Name</th><th>Route</th><th>Action</th></tr></thead>
            <tbody>
              ${state.focalPersons.filter(fp => 
                (fp.name || '').toLowerCase().includes((state.searchQuery || '').toLowerCase()) || 
                (fp.route || '').toLowerCase().includes((state.searchQuery || '').toLowerCase())
              ).map((fp) => `
                <tr>
                  <td>${fp.id}</td>
                  <td>${fp.name}</td>
                  <td>${fp.route}</td>
                  <td><button class="icon-btn" data-focal-delete="${fp.id}">Delete</button></td>
                </tr>
              `).join("") || '<tr><td colspan="4">No focal persons found.</td></tr>'}
            </tbody>
          </table>
        </div>
      </div>
    `;
    return page;
  }

  page.title = "Dashboard";
  page.content = dashboardCards(role);
  return page;
}

function bindPageActions() {
  const role = state.currentUser.role;
  const view = state.currentView;

  if (role === "student" && view === "register") {
    document.getElementById("studentRegisterForm").addEventListener("submit", (e) => {
      e.preventDefault();
      const name = document.getElementById("regName").value.trim();
      const id = document.getElementById("regId").value.trim();
      const route = document.getElementById("regRoute").value;
      if (!name || !id) return;

      let student = state.students.find((s) => s.id === state.currentUser.studentId);
      if (!student) {
        student = { id, name, route, feeStatus: "Unpaid", feeAmount: 5000, lastPaymentDate: "-" };
        state.students.push(student);
      } else {
        student.name = name;
        student.id = id;
        student.route = route;
      }
      state.currentUser.name = name;
      state.currentUser.studentId = id;
      openModal("Registration Saved", `<p>Your request has been successfully sent sent to the admin.</p>`);
      render();
    });
  }

  document.querySelectorAll("[data-pay-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.payId;
      const student = state.students.find((s) => s.id === id);
      if (!student) return;
      student.feeStatus = "Paid";
      student.lastPaymentDate = new Date().toISOString().slice(0, 10);
      openModal("Payment Success", `<p>Fee submitted successfully for ${student.name}.</p>`);
      render();
    });
  });

  document.querySelectorAll("[data-att-id]").forEach((select) => {
    select.addEventListener("change", () => {
      const id = select.dataset.attId;
      const status = select.value;
      let row = state.attendance.find((a) => a.studentId === id);
      if (!row) {
        row = { studentId: id, date: new Date().toISOString().slice(0, 10), status };
        state.attendance.push(row);
      } else {
        row.status = status;
        row.date = new Date().toISOString().slice(0, 10);
      }
      render();
    });
  });

  // Attendance marking buttons - using document-level delegation for all students
  // This ensures clicks work even if table is re-rendered

  document.querySelectorAll("[data-remind-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const st = state.students.find((s) => s.id === btn.dataset.remindId);
      if (!st) return;
      openModal("Reminder Sent", `<p>Fee reminder sent to <strong>${st.name}</strong> (${st.id}).</p>`);
    });
  });

  document.querySelectorAll("[data-fee-id]").forEach((select) => {
    select.addEventListener("change", () => {
      const st = state.students.find((s) => s.id === select.dataset.feeId);
      if (!st) return;
      st.feeStatus = select.value;
      if (st.feeStatus === "Paid") st.lastPaymentDate = new Date().toISOString().slice(0, 10);
      render();
    });
  });

  const sortBtn = document.getElementById("sortByAmountBtn");
  if (sortBtn) {
    sortBtn.addEventListener("click", () => {
      state.sort.order = state.sort.order === "asc" ? "desc" : "asc";
      render();
    });
  }

  const busForm = document.getElementById("busForm");
  if (busForm) {
    busForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const id = document.getElementById("busId").value.trim();
      const model = document.getElementById("busModel").value.trim();
      const capacity = Number(document.getElementById("busCapacity").value);
      const route = document.getElementById("busRoute").value;
      if (!id || !model || !capacity) return;

      const existing = state.buses.find((b) => b.id === id);
      if (existing) {
        existing.model = model;
        existing.capacity = capacity;
        existing.route = route;
      } else {
        state.buses.push({ id, model, capacity, route });
      }
      render();
    });
  }

  document.querySelectorAll("[data-bus-view]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const bus = state.buses.find((b) => b.id === btn.dataset.busView);
      if (!bus) return;
      const route = routeById(bus.route);
      const chips = route ? route.stops.map((s) => `<span class="chip">${s}</span>`).join("") : "";
      openModal(
        `Bus ${bus.id}`,
        `<p><strong>Model:</strong> ${bus.model}</p><p><strong>Capacity:</strong> ${bus.capacity}</p><p><strong>Route:</strong> ${studentRouteName(bus.route)}</p><div>${chips}</div>`
      );
    });
  });

  const routeForm = document.getElementById("routeForm");
  if (routeForm) {
    routeForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const id = document.getElementById("routeId").value.trim();
      const name = document.getElementById("routeName").value.trim();
      const stopsRaw = document.getElementById("routeStops").value.trim();
      const stops = stopsRaw ? stopsRaw.split(",").map((s) => s.trim()).filter(Boolean) : [];
      if (!id || !name) return;

      const existing = state.routes.find((r) => r.id === id);
      if (existing) {
        existing.name = name;
        existing.stops = stops.length ? stops : existing.stops;
      } else {
        state.routes.push({ id, name, stops });
      }
      render();
    });
  }

  
  document.querySelectorAll("[data-route-view]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const route = state.routes.find((r) => r.id === btn.dataset.routeView);
      if (!route) return;
      openModal(route.name, `${route.stops.map((s) => `<span class="chip">${s}</span>`).join("")}`);
    });
  });

  const feedbackForm = document.getElementById("feedbackForm");
  if (feedbackForm) {
    feedbackForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const rating = Number(document.getElementById("transportRating").value);
      const comment = document.getElementById("transportComment").value.trim();
      if (rating < 1 || rating > 5) {
        openModal("Invalid Rating", "Please select a rating between 1 and 5 stars.");
        return;
      }
      const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
      const entry = {
        id: Date.now(),
        studentId: student.id,
        studentName: state.currentUser.name,
        route: student.route,
        rating,
        comment,
        status: "Pending",
        createdAt: new Date().toLocaleString(),
        resolvedAt: null,
        resolvedBy: null
      };
      state.complaints.push(entry);
      openModal("Feedback Submitted", `<p>Thank you for your feedback. Your request is now pending review.</p>`);
      saveState();
      render();
    });
  }

  document.querySelectorAll("[data-resolve-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = Number(btn.dataset.resolveId);
      const item = state.complaints.find((c) => c.id === id);
      if (!item) return;
      item.status = "Resolved";
      item.resolvedAt = new Date().toLocaleString();
      item.resolvedBy = state.currentUser.name;
      openModal("Complaint Resolved", `<p>Ticket ID ${item.id} has been marked as resolved.</p>`);
      saveState();
      render();
    });
  });

  const focalForm = document.getElementById("focalForm");
  if (focalForm) {
    focalForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const id = document.getElementById("focalId").value.trim();
      const name = document.getElementById("focalName").value.trim();
      const route = document.getElementById("focalRoute").value;
      if (!id || !name) return;

      const existing = state.focalPersons.find((fp) => fp.id === id);
      if (existing) {
        existing.name = name;
        existing.route = route;
      } else {
        state.focalPersons.push({ id, name, route });
      }
      render();
    });
  }

  
  const scanBtn = document.getElementById("simulateScanBtn");
  if (scanBtn) {
    scanBtn.addEventListener("click", () => {
      processScan();
    });
  }

  // Admin Dashboard Listeners
  const dlCsv = document.getElementById("downloadCsvBtn");
  if (dlCsv) dlCsv.addEventListener("click", downloadCSVReport);

  const notifForm = document.getElementById("notifForm");
  if (notifForm) {
    notifForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const type = document.getElementById("notifType").value;
      const route = document.getElementById("notifRoute").value;
      const title = document.getElementById("notifTitle").value;
      const message = document.getElementById("notifMsg").value;
      const priority = type === 'emergency' ? 'high' : type === 'delay' ? 'medium' : 'low';
      
      state.notifications.push({
        id: Date.now(),
        type, route, title, message, priority,
        time: "Just now",
        read: false
      });
      state.unreadNotifCount = state.notifications.filter((n) => !n.read).length;
      saveState();
      showToast(title, type);
      render();
    });
  }

  const notifSearch = document.getElementById("notifSearch");
  if (notifSearch) {
    notifSearch.addEventListener("input", (e) => {
      state.notificationFilter = e.target.value;
      state.activeSearchInput = "notifSearch";
      render();
    });
  }

  const clearNotifSearch = document.getElementById("clearNotifSearch");
  if (clearNotifSearch) {
    clearNotifSearch.addEventListener("click", () => {
      state.notificationFilter = "";
      state.activeSearchInput = null;
      render();
    });
  }

  // Unified search handlers are now inline (handleSearch); removing old redundant listeners.

  const clearReportSearch = document.getElementById("clearReportSearch");
  if (clearReportSearch) {
    clearReportSearch.addEventListener("click", () => {
      state.reportSearchQuery = "";
      render();
    });
  }

  const clearNotificationsBtn = document.getElementById("clearNotificationsBtn");
  if (clearNotificationsBtn) {
    clearNotificationsBtn.addEventListener("click", () => {
      const role = state.currentUser?.role;
      if (role !== "admin" && role !== "focal") {
        openModal("Access Denied", "Only Admin or Focal Person can clear all notifications.");
        return;
      }
      if (window.confirm("Delete all notifications?")) {
        state.notifications = [];
        state.notificationFilter = "";
        state.unreadNotifCount = 0;
        saveState();
        render();
      }
    });
  }

  document.querySelectorAll("[data-delete-notif]").forEach((btn) => {
    btn.addEventListener("click", () => {
      deleteNotification(btn.dataset.deleteNotif);
    });
  });

  const bell = document.getElementById("topNavNotifBell");
  if (bell) {
    bell.addEventListener("click", () => {
      openNotifDrawer();
    });
  }

  // Drawer Actions
  const closeDrawer = document.getElementById("closeNotifDrawerBtn");
  if (closeDrawer) {
    closeDrawer.addEventListener("click", closeNotifDrawer);
  }
  const viewFull = document.getElementById("viewAllNotifsBtn");
  if (viewFull) {
    viewFull.addEventListener("click", () => {
      closeNotifDrawer();
      pushNavigationState();
      state.currentView = "notifications";
      render();
    });
  }

  const exportComplaintsCsvBtn = document.getElementById("exportComplaintsCsvBtn");
  if (exportComplaintsCsvBtn) {
    exportComplaintsCsvBtn.addEventListener("click", downloadComplaintsCSV);
  }
  const exportComplaintsPdfBtn = document.getElementById("exportComplaintsPdfBtn");
  if (exportComplaintsPdfBtn) {
    exportComplaintsPdfBtn.addEventListener("click", downloadComplaintsPDF);
  }

  if (state.activeSearchInput) {
    const active = document.getElementById(state.activeSearchInput);
    if (active) {
      active.focus();
      const pos = active.value.length;
      if (typeof active.setSelectionRange === "function") {
        active.setSelectionRange(pos, pos);
      }
    }
  }
  
  // Setup delete event listeners for admin panel management sections
  setupDeleteEventListeners();
}
function openNotifDrawer() {
  const backdrop = document.getElementById("notifDrawerBackdrop");
  const body = document.getElementById("notifDrawerBody");
  if(!backdrop || !body) return;

  // Mark all as read when opening drawer? Or just visit
  state.unreadNotifCount = 0;
  saveState();

  body.innerHTML = state.notifications.map(n => `
    <div class="drawer-notif-item ${n.read ? 'read' : 'unread'}">
      <strong class="dn-title">${n.title}</strong>
      <span class="dn-msg">${n.message}</span>
      <span class="dn-time">${n.time}</span>
    </div>
  `).reverse().join('') || '<div class="empty-state">No notifications yet.</div>';

  backdrop.classList.remove("hidden");
  render(); // Refresh shell to update bell badge
}

function closeNotifDrawer() {
  const backdrop = document.getElementById("notifDrawerBackdrop");
  if(backdrop) backdrop.classList.add("hidden");
}

function saveState() {
  localStorage.setItem("utap_state", JSON.stringify({
    students: state.students,
    routes: state.routes,
    buses: state.buses,
    attendance: state.attendance,
    feeInstallments: state.feeInstallments,
    notifications: state.notifications,
    complaints: state.complaints,
    transportRatings: state.transportRatings,
    registrationRequests: state.registrationRequests,
    currentUser: state.currentUser,
    currentView: state.currentView,
    showLogin: state.showLogin,
    showRegistration: state.showRegistration,
    loginRolePreset: state.loginRolePreset
  }));
  
  // Update last activity timestamp when user is logged in
  if (state.currentUser) {
    localStorage.setItem('utap_last_activity', Date.now().toString());
  }
}

function clearState() {
  localStorage.removeItem("utap_state");
  localStorage.removeItem('utap_last_activity');
  location.reload();
}

function loadState() {
  const saved = localStorage.getItem("utap_state");
  if (saved) {
    const data = JSON.parse(saved);
    state.students = data.students || state.students;
    state.routes = data.routes || state.routes;
    state.buses = data.buses || state.buses;
    state.attendance = data.attendance || state.attendance;
    state.notifications = data.notifications || state.notifications;
    state.unreadNotifCount = data.unreadNotifCount || 0;
    state.complaints = data.complaints || state.complaints;
    state.transportRatings = data.transportRatings || state.transportRatings;
    state.registrationRequests = data.registrationRequests || state.registrationRequests;
    
    // Restore user session and current view
    if (data.currentUser) {
      state.currentUser = data.currentUser;
      state.currentView = data.currentView || "dashboard";
      state.showLogin = data.showLogin || false;
      state.showRegistration = data.showRegistration || false;
      state.loginRolePreset = data.loginRolePreset || "student";
    }
  }
}

// Session validation and cleanup
function validateSession() {
  if (state.currentUser) {
    // Check if session is not too old (24 hours)
    const lastActivity = localStorage.getItem('utap_last_activity');
    const now = Date.now();
    const SESSION_TIMEOUT = 24 * 60 * 60 * 1000; // 24 hours
    
    if (lastActivity && (now - parseInt(lastActivity)) > SESSION_TIMEOUT) {
      // Session expired, logout user
      state.currentUser = null;
      state.currentView = "dashboard";
      state.showLogin = true;
      localStorage.removeItem('utap_last_activity');
      saveState();
      return false;
    }
    
    // Update last activity
    localStorage.setItem('utap_last_activity', now.toString());
  }
  return true;
}

// Initial Load - Clear any saved session to ensure landing page shows
localStorage.removeItem("utap_state");
localStorage.removeItem('utap_last_activity');
loadState();

// Force show landing page on initial load
state.currentUser = null;
state.showLogin = false;
state.showRegistration = false;
state.currentView = "dashboard";
render();

function renderStudentAttendanceView() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const studentAttendance = state.attendance.filter((a) => a.studentId === student.id);
  
  // Calculate attendance statistics
  const totalDays = studentAttendance.length;
  const presentDays = studentAttendance.filter((a) => a.status === "Present").length;
  const absentDays = studentAttendance.filter((a) => a.status === "Absent").length;
  const attendancePercentage = totalDays > 0 ? ((presentDays / totalDays) * 100).toFixed(1) : 0;
  
  // Sort attendance by date (newest first)
  const sortedAttendance = [...studentAttendance].sort((a, b) => new Date(b.date) - new Date(a.date));
  
  return `
    <div class="attendance-container">
      <div class="attendance-summary-cards">
        <div class="card">
          <h3>Attendance Summary</h3>
          <div class="summary-stats">
            <div class="stat-item">
              <span class="stat-value">${attendancePercentage}%</span>
              <span class="stat-label">Attendance Rate</span>
            </div>
            <div class="stat-item">
              <span class="stat-value present">${presentDays}</span>
              <span class="stat-label">Days Present</span>
            </div>
            <div class="stat-item">
              <span class="stat-value absent">${absentDays}</span>
              <span class="stat-label">Days Absent</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">${totalDays}</span>
              <span class="stat-label">Total Days</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <h3>Daily Attendance Record</h3>
          <button class="btn-primary" onclick="downloadAttendancePDF()">
            <i class="fas fa-download"></i> Download PDF
          </button>
        </div>
        
        <div class="table-container">
          <table class="attendance-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Day</th>
              </tr>
            </thead>
            <tbody>
              ${sortedAttendance.length > 0 ? sortedAttendance.map((record) => {
                const date = new Date(record.date);
                const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
                const statusClass = record.status === "Present" ? "present" : "absent";
                const statusIcon = record.status === "Present" ? "✓" : "✗";
                
                return `
                  <tr>
                    <td>${record.date}</td>
                    <td class="status-${statusClass}">
                      <span class="status-indicator">${statusIcon}</span>
                      ${record.status}
                    </td>
                    <td>${dayName}</td>
                  </tr>
                `;
              }).join('') : `
                <tr>
                  <td colspan="3" class="no-data">No attendance records found</td>
                </tr>
              `}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

function downloadAttendancePDF() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const studentAttendance = state.attendance.filter((a) => a.studentId === student.id);
  
  const totalDays = studentAttendance.length;
  const presentDays = studentAttendance.filter((a) => a.status === "Present").length;
  const absentDays = studentAttendance.filter((a) => a.status === "Absent").length;
  const attendancePercentage = totalDays > 0 ? ((presentDays / totalDays) * 100).toFixed(1) : 0;
  
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'Student Attendance Report');
  
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text(`Student Name: ${student.name}`, 20, startY);
  doc.text(`Student ID: ${student.id}`, 20, startY + 7);
  doc.text(`Attendance Rate: ${attendancePercentage}%`, 20, startY + 14);
  
  const tableData = studentAttendance.map(record => {
    const date = new Date(record.date);
    const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
    return [record.date, dayName, record.status];
  });
  
  doc.autoTable({
    startY: startY + 25,
    head: [['Date', 'Day', 'Status']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [62, 13, 72], textColor: [255, 255, 255], fontStyle: 'bold' },
    alternateRowStyles: { fillColor: [248, 240, 255] }
  });
  
  doc.save(`attendance_report_${student.id}_${new Date().toISOString().split('T')[0]}.pdf`);
}

function renderBusPass() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const route = routeById(student.route);
  const expiryDate = "June 2026";
  const qrData = `UTAP-STU-${student.id}-${student.route}`;

  return `
    <div class="pass-container">
      <div class="pass-card card">
        <div class="pass-header">
          <div class="pass-logo-wrap">
            <span class="univ-logo-placeholder">S</span>
            <div class="pass-header-text">
              <h3>Superior University</h3>
            
            </div>
          </div>
          <div class="pass-badge">Active</div>
        </div>
        
        <div class="pass-body">
          <div class="pass-avatar-wrap">
            <div class="avatar-circle">${student.name.charAt(0)}</div>
          </div>
          
          <div class="pass-info-grid">
            <div class="info-item">
              <label>Full Name</label>
              <strong>${student.name}</strong>
            </div>
            <div class="info-item">
              <label>Student ID</label>
              <strong>${student.id}</strong>
            </div>
            <div class="info-item">
              <label>Bus Route</label>
              <strong>${route ? route.name : "Unassigned"}</strong>
            </div>
            <div class="info-item">
              <label>Valid Until</label>
              <strong>${expiryDate}</strong>
            </div>
          </div>
          
          <div class="pass-qr-wrap">
             <div class="qr-canvas-mock">
                <div class="qr-box-inner">
                  <svg viewBox="0 0 100 100" fill="var(--purple-900)">
                    <path d="M10 10h30v30H10zM10 60h30v30H10zM60 10h30v30H60zM60 60h30v10H80v20H60zM40 40h10v10H40zM50 50h10v10H50z" />
                  </svg>
                </div>
             </div>
             <p class="qr-data-label">${qrData}</p>
          </div>
        </div>
        
        <div class="pass-footer">
          <div class="p-foot-item">
             <small>Stop</small>
             <span>${route ? route.stops[0] : "N/A"}</span>
          </div>
          <div class="p-foot-item">
             <small>Tier</small>
             <span class="gold-text">Student Premium</span>
          </div>
        </div>
      </div>
      
      <div class="pass-actions">
        <button class="btn-primary" onclick="alert('Digital Pass Exported!')"><i class="fas fa-download"></i> Save as PDF</button>
        <p class="pass-subtle">Present this QR code for scanning at the bus entrance.</p>
      </div>
    </div>
  `;
}

function renderScanner() {
  return `
    <div class="scanner-interface card">
      <div class="scanner-top">
        <h2>Attendance Terminal</h2>
        <p>Driver / Staff Mode: Scan QR to board students</p>
      </div>
      
      <div class="scanner-view-wrap">
        <div class="scanner-box">
          <div class="scanner-scanner"></div>
          <div class="scanner-overlay">
            <div class="target-corners"></div>
            <div class="scan-line"></div>
          </div>
          <div class="scanner-no-cam">
             <i class="fas fa-qrcode"></i>
             <p>Awaiting Code Presence...</p>
          </div>
        </div>
        
        <div class="scanner-sidebar">
          <div class="feedback-area" id="scanFeedback">
             <div class="feedback-idle">Ready to Scan</div>
          </div>
          <button class="btn-primary full-width" id="simulateScanBtn">Simulate Capture [BEEP]</button>
          
          <div class="boarding-log">
             <h3>Recent Boardings</h3>
             <div class="log-list" id="scanLogList">
                <div class="log-empty">No activity today</div>
             </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

function processScan() {
  const student = state.students[Math.floor(Math.random() * state.students.length)];
  const now = new Date();
  const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  
  // Update State
  state.attendance.push({
    studentId: student.id,
    date: now.toISOString().slice(0, 10),
    status: "Present"
  });
  
  // UI Feedbacks
  const feedback = document.getElementById("scanFeedback");
  const logList = document.getElementById("scanLogList");
  
  if (feedback) {
    feedback.innerHTML = `
      <div class="success-alert">
         <i class="fas fa-check-circle"></i>
         <div class="sa-text">
            <strong>Boarded: ${student.name}</strong>
            <span>Verified Student ID: ${student.id}</span>
         </div>
      </div>
    `;
    setTimeout(() => {
      if (feedback) feedback.innerHTML = '<div class="feedback-idle">Ready to Scan</div>';
    }, 3000);
  }
  
  if (logList) {
    if (logList.querySelector(".log-empty")) logList.innerHTML = "";
    const entry = document.createElement("div");
    entry.className = "log-entry";
    entry.innerHTML = `
      <div class="le-student">
        <strong>${student.name}</strong>
        <span>ID: ${student.id}</span>
      </div>
      <div class="le-time">${timeStr}</div>
    `;
    logList.prepend(entry);
  }
  
  // Audio Feedback (Beep)
  const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  const osc = audioCtx.createOscillator();
  const gain = audioCtx.createGain();
  osc.type = "sine";
  osc.frequency.setValueAtTime(880, audioCtx.currentTime);
  gain.gain.setValueAtTime(1, audioCtx.currentTime);
  gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
  osc.connect(gain);
  gain.connect(audioCtx.destination);
  osc.start();
  osc.stop(audioCtx.currentTime + 0.1);
}


function renderNotificationsView(role) {
  state.unreadNotifCount = 0;
  const filteredNotifs = filterNotifications(state.notificationFilter);
  const total = state.notifications.length;
  const visible = filteredNotifs.length;
  const canManage = role === "admin" || role === "focal";

  return `
    <div class="notif-history">
      <div class="history-header">
        <h3>System Updates</h3>
        <p>Real-time alerts and official university transport announcements</p>
      </div>
      <div class="search-container mt-2">
        <div class="search-icon-box"><i class="fas fa-search"></i></div>
        <input type="text" id="notifSearch" class="search-input" placeholder="Search updates by text, route, or type..." value="${state.notificationFilter}" oninput="handleNotifSearch(this.value)">
      </div>
      <div class="p-1">
        ${canManage ? `<button id="clearNotificationsBtn" class="btn-soft mb-1">Clear All Notifications</button>` : ""}
      </div>
      <div class="notif-summary"><span>Showing ${visible} of ${total} notifications</span></div>
      <div class="notif-list">
        ${filteredNotifs.length ? filteredNotifs.map(n => `
          <div class="notif-item ${n.read ? 'read' : 'unread'} p-${n.priority}">
            <div class="notif-icon-wrap">
              ${n.type === 'delay' ? '<i class="fas fa-route"></i>' : n.type === 'emergency' ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-bullhorn"></i>'}
            </div>
            <div class="notif-content">
              <div class="notif-meta">
                <span class="notif-type-tag">${n.type.replace('_', ' ').toUpperCase()}</span>
                <span class="notif-time"><i class="far fa-clock"></i> ${n.time}</span>
              </div>
              <h4 class="notif-title">${n.title}</h4>
              <p class="notif-msg">${n.message}</p>
              ${n.route !== 'All' ? `<span class="notif-route"><i class="fas fa-bus-alt"></i> Route: ${n.route}</span>` : '<span class="notif-route"><i class="fas fa-globe"></i> All Routes</span>'}
            </div>
            ${canManage ? `<div class="notif-actions"><button class="btn-soft notif-delete-btn" data-delete-notif="${n.id}">Delete</button></div>` : ""}
          </div>
        `).reverse().join('') : '<div class="empty-state">No notifications to show.</div>'}
      </div>
    </div>
  `;
}

function renderFeedbackView() {
  const student = state.students.find((s) => s.id === state.currentUser.studentId) || state.students[0];
  const existing = state.complaints.filter((c) => c.studentId === student.id);
  const avg = existing.length ? (existing.reduce((sum, c) => sum + c.rating, 0) / existing.length).toFixed(1) : "--";

  return `
    <div class="grid">
      <div class="card span-6">
        <h3>Rate Your Transport</h3>
        <form id="feedbackForm" class="form">
          <div><label>Bus Route</label><input value="${studentRouteName(student.route)}" readonly /></div>
          <div>
            <label>Rating (1-5 stars)</label>
            <input id="transportRating" type="number" min="1" max="5" value="5" required />
          </div>
          <div>
            <label>Describe your experience</label>
            <textarea id="transportComment" rows="4" placeholder="Safe driver, clean bus, time management, etc..."></textarea>
          </div>
          <button class="btn-primary" type="submit">Submit Feedback</button>
        </form>
      </div>
      <div class="card span-6">
        <h3>My Feedback Summary</h3>
        <p>Average rating: <strong>${avg}</strong></p>
        <p>Total entries: <strong>${existing.length}</strong></p>
        <div class="feedback-list">
          ${existing.length ? existing.map((c) => `
            <article class="feedback-item">
              <h4>Rating: ${c.rating} ★</h4>
              <small class="hint">${c.createdAt} · ${c.status}</small>
              <p>${c.comment || "(No comment)"}</p>
            </article>
          `).join('') : `<p class="empty-state">No feedback submitted yet.</p>`}
        </div>
      </div>
    </div>
  `;
}

function renderAdminComplaints() {
  const pending = state.complaints.filter((c) => c.status === "Pending");
  const resolved = state.complaints.filter((c) => c.status === "Resolved");

  return `
    <div class="grid">
      <div class="card span-12">
        <h3>Student Complaints & Rating Tickets</h3>
        <p class="pass-subtle">Action required: resolve student issues and comment requests from transport service</p>
        <div class="export-actions mb-1">
           <button id="exportComplaintsCsvBtn" class="btn-soft">Export Complaints CSV</button>
           <button id="exportComplaintsPdfBtn" class="btn-soft">Export Complaints PDF</button>
        </div>
        <div class="complaints-summary">
          <span><strong>${state.complaints.length}</strong> total tickets</span>
          <span><strong>${pending.length}</strong> pending</span>
          <span><strong>${resolved.length}</strong> resolved</span>
        </div>
        <table>
          <thead><tr><th>ID</th><th>Student</th><th>Route</th><th>Rating</th><th>Comment</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            ${state.complaints.map((c) => `
              <tr>
                <td>${c.id}</td>
                <td>${c.studentName || c.studentId}</td>
                <td>${studentRouteName(c.route)}</td>
                <td>${c.rating} ★</td>
                <td>${c.comment || "-"}</td>
                <td>${c.status}</td>
                <td>
                  ${c.status === "Pending" ? `<button class="btn-soft" data-resolve-id="${c.id}">Resolve</button>` : `<small>Resolved by ${c.resolvedBy || "n/a"}</small>`}
                </td>
              </tr>
            `).join('') || `<tr><td colspan="7">No complaints or reviews yet.</td></tr>`}
          </tbody>
        </table>
      </div>
    </div>
  `;
}

function renderSendNotificationView() {
  return `
    <div class="grid">
      <div class="card span-6">
        <h3>Create Notification</h3>
        <form id="notifForm" class="form">
          <div>
            <label>Notification Type</label>
            <select id="notifType">
              <option value="info">General Info (Blue)</option>
              <option value="delay">Bus Delay (Yellow)</option>
              <option value="emergency">Emergency Alert (Red)</option>
              <option value="route_change">Route Change</option>
            </select>
          </div>
          <div>
            <label>Target Route</label>
            <select id="notifRoute">
              <option value="All">All Routes</option>
              ${state.routes.map(r => `<option value="${r.id}">${r.id}</option>`).join('')}
            </select>
          </div>
          <div>
            <label>Title</label>
            <input id="notifTitle" placeholder="e.g., Bus R-01 Delayed" required />
          </div>
          <div>
            <label>Message Content</label>
            <textarea id="notifMsg" rows="4" placeholder="Type your message here..." required></textarea>
          </div>
          <button class="btn-primary" type="submit">Broadcast System-Wide</button>
        </form>
      </div>
      <div class="card span-6">
        <h3>Standard Templates</h3>
        <div class="template-list">
           <button class="btn-soft full-width mb-1" onclick="applyTemplate('delay')">Bus Late (15 mins)</button>
           <button class="btn-soft full-width mb-1" onclick="applyTemplate('emergency')">Emergency: Mechanical Issue</button>
           <button class="btn-soft full-width" onclick="applyTemplate('holiday')">Holiday Notice</button>
        </div>
        <p class="pass-subtle mt-1 text-center">Note: Notifications are sent in real-time to all matching students.</p>
      </div>
    </div>
  `;
}


window.applyTemplate = function(type) {
  const title = document.getElementById('notifTitle');
  const msg = document.getElementById('notifMsg');
  const typeSel = document.getElementById('notifType');
  if(!title || !msg || !typeSel) return;

  if (type === 'delay') {
    title.value = "Route Update: Bus Delay";
    msg.value = "Bus is running 15 minutes late due to heavy traffic on the canal road. Please stay at your stop.";
    typeSel.value = "delay";
  } else if (type === 'emergency') {
    title.value = "🚨 EMERGENCY ALERT";
    msg.value = "Bus R-04 delayed due to traffic. Backup bus is being dispatched. Please wait at your stop.";
    typeSel.value = "emergency";
  } else if (type === 'holiday') {
    title.value = "Holiday Notice (10 April)";
    msg.value = "University transport services will remain suspended on 10 April 2026 due to the public holiday.";
    typeSel.value = "info";
  }
};

function showToast(title, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  // Use professional blue icons
  let icon = 'fa-info-circle';
  if(type === 'emergency') icon = 'fa-shield-alt';
  if(type === 'delay') icon = 'fa-hourglass-half';

  toast.innerHTML = `
    <div class="notif-icon-wrap" style="width:40px; height:40px; font-size:1.1rem; margin-right:5px;">
       <i class="fas ${icon}"></i>
    </div>
    <div class="toast-text">
      <strong>${title}</strong>
      <span>System Broadcast · Just now</span>
    </div>
  `;
  container.appendChild(toast);
  
  try {
     const audio = new (window.AudioContext || window.webkitAudioContext)();
     const osc = audio.createOscillator();
     const gain = audio.createGain();
     osc.type = "triangle";
     osc.frequency.setValueAtTime(type === 'emergency' ? 220 : 440, audio.currentTime);
     gain.gain.setValueAtTime(0.5, audio.currentTime);
     gain.gain.exponentialRampToValueAtTime(0.01, audio.currentTime + 0.8);
     osc.connect(gain);
     gain.connect(audio.destination);
     osc.start();
     osc.stop(audio.currentTime + 0.8);
  } catch(e){}

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(30px) scale(0.95)';
    setTimeout(() => toast.remove(), 600);
  }, 6000);
}

function renderAdminDashboard() {
  const totalStudents = state.students.length;
  const totalRoutes = state.routes.length;
  const totalBuses = state.buses.length;
  const totalCapacity = state.buses.reduce((acc, b) => acc + b.capacity, 0);
  const usagePct = Math.round((totalStudents / totalCapacity) * 100);
  const paidCount = state.students.filter(s => s.feeStatus === 'Paid').length;
  const feePct = Math.round((paidCount / totalStudents) * 100);

  return `
    <div class="grid">
      <!-- Top Stats Cards -->
      <div class="card span-3">
        <div class="stats">
           <i class="fas fa-users-viewfinder stats-icon-blue"></i>
           <span class="label">Total Registered</span>
           <span class="value">${totalStudents}</span>
           <p class="stats-subtext">Verified Students</p>
        </div>
      </div>
      <div class="card span-3">
        <div class="stats">
           <i class="fas fa-map-marked stats-icon-blue"></i>
           <span class="label">Active Routes</span>
           <span class="value">${totalRoutes}</span>
           <p class="stats-subtext">All Operational</p>
        </div>
      </div>
      <div class="card span-3">
        <div class="stats">
           <i class="fas fa-bus-alt stats-icon-blue"></i>
           <span class="label">Fleet Size</span>
           <span class="value">${totalBuses}</span>
           <p class="stats-subtext">Buses Online</p>
        </div>
      </div>
      <div class="card span-3">
        <div class="stats">
           <i class="fas fa-id-badge stats-icon-blue"></i>
           <span class="label">Staff Members</span>
           <span class="value">${state.focalPersons.length}</span>
           <p class="stats-subtext">Active Focals</p>
        </div>
      </div>

      <!-- Usage Analytics -->
      <div class="card span-8">
        <h3>Operational Analytics</h3>
        <p class="pass-subtle">Real-time usage and collection metrics</p>
        
        <div class="analytics-item">
           <div class="analytics-meta">
              <span>Bus Seat Usage</span>
              <span>${usagePct}%</span>
           </div>
           <div class="analytics-bar-bg">
              <div class="analytics-bar-fill blue-fill" style="width: ${usagePct}%"></div>
           </div>
           <p class="analytics-hint">${totalStudents} students sharing ${totalCapacity} available seats.</p>
        </div>

        <div class="analytics-item mt-2">
           <div class="analytics-meta">
              <span>Fee Collection Rate</span>
              <span>${feePct}%</span>
           </div>
           <div class="analytics-bar-bg">
              <div class="analytics-bar-fill purple-fill" style="width: ${feePct}%"></div>
           </div>
           <p class="analytics-hint">${paidCount} out of ${totalStudents} students have paid their semester fee.</p>
        </div>
      </div>

      <!-- Action Card -->
      <div class="card span-4">
        <h3>Export Reports</h3>
        <p class="pass-subtle">Generate professional management reports</p>
        <div class="export-actions">
           <button id="downloadPdfBtn" class="btn-primary full-width mb-1" onclick="downloadAdminPDFReport()">
              <i class="fas fa-file-pdf"></i> Download PDF Report
           </button>
           <button id="downloadFeeStatusBtn" class="btn-primary full-width mb-1" onclick="downloadAdminFeeStatusPDF()">
              <i class="fas fa-file-invoice-dollar"></i> Fee Status Report
           </button>
           <button id="downloadCsvBtn" class="btn-soft full-width" onclick="downloadAdminCSVReport()">
              <i class="fas fa-file-excel"></i> Export Excel Data (CSV)
           </button>
        </div>
        <div class="p-1 mt-1 bg-soft border-rounded">
           <p style="font-size:0.8rem; line-height:1.4;">
             <strong>Security Note:</strong> These reports contain student PII. Please handle with care per University policy.
           </p>
        </div>
      </div>
    </div>
  `;
}

function downloadAdminPDFReport() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'System Management Report');
  
  const totalStudents = state.students.length;
  const paidStudents = state.students.filter(s => s.feeStatus === 'Paid').length;
  const unpaidStudents = state.students.filter(s => s.feeStatus === 'Unpaid').length;
  
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text('System Statistics Summary:', 20, startY);
  
  const statsData = [
    ['Metric', 'Count'],
    ['Total Registered Students', totalStudents.toString()],
    ['Paid Students', paidStudents.toString()],
    ['Unpaid Students', unpaidStudents.toString()],
    ['Total Buses', state.buses.length.toString()],
    ['Total Routes', state.routes.length.toString()]
  ];
  
  doc.autoTable({
    startY: startY + 10,
    body: statsData,
    theme: 'plain',
    styles: { fontSize: 10 },
    columnStyles: { 0: { fontStyle: 'bold', width: 60 } }
  });
  
  const routesData = state.routes.map(r => [r.id, r.name, r.catalogTitle, r.yearlyFare]);
  
  doc.autoTable({
    startY: doc.lastAutoTable.finalY + 15,
    head: [['ID', 'Route Name', 'Description', 'Fare']],
    body: routesData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [62, 13, 72], textColor: [255, 255, 255], fontStyle: 'bold' }
  });
  
  doc.save(`admin_system_report_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Admin report PDF downloaded', 'success');
}

function downloadAdminFeeStatusPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  doc.setFontSize(20);
  doc.text('Fee Status Report', 105, 20, { align: 'center' });
  
  doc.setFontSize(12);
  doc.text(`Generated: ${new Date().toLocaleDateString()}`, 20, 40);
  
  let yPosition = 60;
  
  // Group students by fee status
  const paidStudents = state.students.filter(s => s.feeStatus === 'Paid');
  const unpaidStudents = state.students.filter(s => s.feeStatus === 'Unpaid');
  
  // Paid Students
  doc.setFontSize(14);
  doc.text(`Paid Students (${paidStudents.length}):`, 20, yPosition);
  yPosition += 10;
  
  doc.setFontSize(10);
  paidStudents.forEach(student => {
    if (yPosition > 270) {
      doc.addPage();
      yPosition = 20;
    }
    doc.text(`${student.name} (${student.id}) - PKR ${student.feeAmount} - ${student.lastPaymentDate}`, 30, yPosition);
    yPosition += 7;
  });
  
  yPosition += 10;
  
  // Unpaid Students
  doc.setFontSize(14);
  doc.text(`Unpaid Students (${unpaidStudents.length}):`, 20, yPosition);
  yPosition += 10;
  
  doc.setFontSize(10);
  unpaidStudents.forEach(student => {
    if (yPosition > 270) {
      doc.addPage();
      yPosition = 20;
    }
    doc.text(`${student.name} (${student.id}) - PKR ${student.feeAmount} - Route: ${student.route}`, 30, yPosition);
    yPosition += 7;
  });
  
  doc.save(`fee_status_report_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Fee status PDF downloaded', 'success');
}

function downloadAdminCSVReport() {
  let csvContent = "Student ID,Name,Route,Fee Status,Fee Amount,Last Payment Date\n";
  
  state.students.forEach(student => {
    csvContent += `${student.id},"${student.name}",${student.route},${student.feeStatus},${student.feeAmount},${student.lastPaymentDate}\n`;
  });
  
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `students_data_${new Date().toISOString().split('T')[0]}.csv`;
  a.click();
  window.URL.revokeObjectURL(url);
  
  showToast('CSV data exported', 'success');
}

function downloadCSVReport() {
  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "Student ID,Name,Route,Fee Status,Amount\n";
  
  state.students.forEach(s => {
    csvContent += `${s.id},${s.name},${s.route},${s.feeStatus},${s.feeAmount}\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", `UTAP_Report_${Date.now()}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast("CSV Exported successfully", "info");
}

function downloadComplaintsCSV() {
  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "ID,Student ID,Student Name,Route,Rating,Comment,Status,Created At,Resolved At,Resolved By\n";
  state.complaints.forEach(c => {
    const commentSafe = c.comment ? c.comment.replace(/\n/g, " ").replace(/,/g, ";") : "";
    csvContent += `${c.id},${c.studentId},${c.studentName},${c.route},${c.rating},${commentSafe},${c.status},${c.createdAt || ""},${c.resolvedAt || ""},${c.resolvedBy || ""}\n`;
  });
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", `UTAP_Complaints_Report_${Date.now()}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast("Complaints CSV Exported", "info");
}

function downloadComplaintsPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  doc.setFontSize(16);
  doc.text("Superior University UTAP Complaints Report", 15, 20);
  doc.setFontSize(10);
  let y = 30;

  state.complaints.forEach((c, index) => {
    if (y > 270) {
      doc.addPage();
      y = 20;
    }
    doc.text(`${index + 1}. ${c.studentName} (${c.studentId}) | ${c.route} | ${c.rating}★`, 15, y);
    y += 6;
    const summary = `Status: ${c.status}, Created: ${c.createdAt || "N/A"}, Resolved: ${c.resolvedAt || "N/A"} by ${c.resolvedBy || "N/A"}`;
    doc.text(summary, 18, y);
    y += 6;
    const comment = `Comment: ${c.comment || "(No comment)"}`;
    doc.text(comment, 18, y);
    y += 8;
  });

  doc.save(`UTAP_Complaints_Report_${Date.now()}.pdf`);
  showToast("Complaints PDF Downloaded", "info");
}

function renderFeeRemindersView(unpaidStudents) {
  const messageTemplates = [
    {
      id: "gentle",
      name: "Gentle Reminder",
      subject: "Transport Fee Reminder - Superior University",
      message: "Dear Student,\n\nThis is a friendly reminder that your transport fee for the current installment is due. Please ensure timely payment to avoid any disruption in transport services.\n\nThank you,\nSuperior University Transport Department"
    },
    {
      id: "urgent",
      name: "Urgent Reminder",
      subject: "URGENT: Transport Fee Payment Required",
      message: "Dear Student,\n\nYour transport fee payment is now overdue. Please make the payment immediately to continue using the transport service.\n\nImmediate action required.\n\nRegards,\nSuperior University Transport Department"
    },
    {
      id: "final",
      name: "Final Notice",
      subject: "FINAL NOTICE: Transport Fee Suspension",
      message: "Dear Student,\n\nThis is the final notice regarding your overdue transport fee. Your transport service will be suspended if payment is not made within 48 hours.\n\nPlease contact the accounts department immediately.\n\nSuperior University Transport Department"
    }
  ];

  return `
    <div class="fee-reminders-container">
      <div class="card">
        <h3>Send Fee Reminders</h3>
        <div class="reminder-stats">
          <div class="stat-item">
            <span class="stat-value">${unpaidStudents.length}</span>
            <span class="stat-label">Unpaid Students</span>
          </div>
        </div>
        
        <div class="message-template-section">
          <h4>Message Template</h4>
          <select id="messageTemplate" onchange="selectMessageTemplate()">
            <option value="">Select a template...</option>
            ${messageTemplates.map(template => `
              <option value="${template.id}">${template.name}</option>
            `).join('')}
          </select>
          
          <div class="message-form">
            <div class="form-group">
              <label>Subject</label>
              <input type="text" id="messageSubject" placeholder="Enter subject..." />
            </div>
            <div class="form-group">
              <label>Message</label>
              <textarea id="messageContent" rows="6" placeholder="Enter your message..."></textarea>
            </div>
          </div>
        </div>
        
        <div class="reminder-actions">
          <button class="btn-primary" onclick="sendRemindersToAll()">
            <i class="fas fa-paper-plane"></i> Send to All Unpaid Students
          </button>
          <button class="btn-soft" onclick="copyMessageToClipboard()">
            <i class="fas fa-copy"></i> Copy Message
          </button>
        </div>
      </div>
      
      <div class="card">
        <h3>Individual Student Reminders</h3>
        <div class="table-container">
          <table class="fee-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Route</th>
                <th>Fee Amount</th>
                <th>Overdue Days</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              ${unpaidStudents.map(student => {
                const overdueDays = Math.floor((new Date() - new Date(student.lastPaymentDate)) / (1000 * 60 * 60 * 24));
                return `
                  <tr>
                    <td>${student.name} (${student.id})</td>
                    <td>${studentRouteName(student.route)}</td>
                    <td>PKR ${student.feeAmount}</td>
                    <td class="${overdueDays > 30 ? 'overdue' : ''}">${overdueDays} days</td>
                    <td>
                      <button class="btn-soft" onclick="sendIndividualReminder('${student.id}')">
                        <i class="fas fa-bell"></i> Send Reminder
                      </button>
                    </td>
                  </tr>
                `;
              }).join('') || '<tr><td colspan="5">No unpaid students found</td></tr>'}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

function selectMessageTemplate() {
  const templates = [
    {
      id: "gentle",
      subject: "Transport Fee Reminder - Superior University",
      message: "Dear Student,\n\nThis is a friendly reminder that your transport fee for the current installment is due. Please ensure timely payment to avoid any disruption in transport services.\n\nThank you,\nSuperior University Transport Department"
    },
    {
      id: "urgent",
      subject: "URGENT: Transport Fee Payment Required",
      message: "Dear Student,\n\nYour transport fee payment is now overdue. Please make the payment immediately to continue using the transport service.\n\nImmediate action required.\n\nRegards,\nSuperior University Transport Department"
    },
    {
      id: "final",
      subject: "FINAL NOTICE: Transport Fee Suspension",
      message: "Dear Student,\n\nThis is the final notice regarding your overdue transport fee. Your transport service will be suspended if payment is not made within 48 hours.\n\nPlease contact the accounts department immediately.\n\nSuperior University Transport Department"
    }
  ];
  
  const selectedTemplate = document.getElementById('messageTemplate').value;
  const template = templates.find(t => t.id === selectedTemplate);
  
  if (template) {
    document.getElementById('messageSubject').value = template.subject;
    document.getElementById('messageContent').value = template.message;
  }
}

function sendRemindersToAll() {
  const subject = document.getElementById('messageSubject').value;
  const message = document.getElementById('messageContent').value;
  
  if (!subject || !message) {
    showToast('Please enter both subject and message', 'error');
    return;
  }
  
  const unpaidStudents = state.students.filter(s => s.feeStatus === 'Unpaid');
  showToast(`Sending reminders to ${unpaidStudents.length} students...`, 'success');
  
  // Simulate sending (in real app, this would send emails/SMS)
  setTimeout(() => {
    showToast(`Reminders sent to ${unpaidStudents.length} students successfully!`, 'success');
  }, 2000);
}

function sendIndividualReminder(studentId) {
  const subject = document.getElementById('messageSubject').value || "Transport Fee Reminder";
  const message = document.getElementById('messageContent').value || "Please pay your transport fee.";
  const student = state.students.find(s => s.id === studentId);
  
  showToast(`Sending reminder to ${student.name}...`, 'success');
  
  // Simulate sending (in real app, this would send email/SMS)
  setTimeout(() => {
    showToast(`Reminder sent to ${student.name}!`, 'success');
  }, 1500);
}

function copyMessageToClipboard() {
  const subject = document.getElementById('messageSubject').value;
  const message = document.getElementById('messageContent').value;
  
  if (!subject || !message) {
    showToast('Please enter both subject and message', 'error');
    return;
  }
  
  const fullMessage = `Subject: ${subject}\n\n${message}`;
  navigator.clipboard.writeText(fullMessage).then(() => {
    showToast('Message copied to clipboard!', 'success');
  }).catch(() => {
    showToast('Failed to copy message', 'error');
  });
}

// Document-level event delegation for attendance buttons (works for all students)
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-attendance-id]');
  if (btn) {
    e.preventDefault();
    e.stopPropagation();
    const id = btn.dataset.attendanceId;
    const status = btn.dataset.attendanceStatus;
    markAttendance(id, status);
  }
});

function markAttendance(studentId, status) {
  const today = new Date().toISOString().split('T')[0];
  const existingIndex = state.attendance.findIndex(a => a.studentId === studentId && a.date === today);

  if (existingIndex >= 0) {
    state.attendance[existingIndex].status = status;
  } else {
    state.attendance.push({
      studentId: studentId,
      date: today,
      status: status
    });
  }

  saveState();

  // Update only the specific row's status cell to prevent page from shaking
  const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
  if (row) {
    const statusCell = row.querySelector('[data-status-cell="true"]');
    if (statusCell) {
      const statusClass = status === "Present" ? "status-present" : status === "Absent" ? "status-absent" : "status-pending";
      statusCell.className = statusClass;
      statusCell.textContent = status;
    }
  }
}

// Make markAttendance globally available
window.markAttendance = markAttendance;

function downloadAttendanceHistoryPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'Detailed Attendance History');
  
  const tableData = state.attendance.map(record => {
    const student = state.students.find(s => s.id === record.studentId);
    return [
      record.date,
      student ? student.name : 'Unknown',
      record.studentId,
      record.status
    ];
  });
  
  doc.autoTable({
    startY: startY,
    head: [['Date', 'Student Name', 'Student ID', 'Status']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [62, 13, 72], textColor: [255, 255, 255], fontStyle: 'bold' },
    alternateRowStyles: { fillColor: [248, 240, 255] }
  });
  
  doc.save(`attendance_history_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Attendance history PDF downloaded', 'success');
}

function downloadUnpaidStudentsPDF() {
  const unpaidStudents = state.students.filter(s => s.feeStatus === 'Unpaid');
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'Overdue Payments Report');
  
  doc.setFontSize(12);
  doc.setTextColor(200, 0, 0);
  doc.text(`Total Students with Overdue Payments: ${unpaidStudents.length}`, 20, startY);
  
  const tableData = unpaidStudents.map(s => [
    s.id,
    s.name,
    studentRouteName(s.route),
    `PKR ${s.feeAmount.toLocaleString()}`,
    s.lastPaymentDate
  ]);
  
  doc.autoTable({
    startY: startY + 10,
    head: [['ID', 'Name', 'Route', 'Amount Due', 'Last Payment']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [161, 14, 90], textColor: [255, 255, 255], fontStyle: 'bold' },
    alternateRowStyles: { fillColor: [255, 240, 245] }
  });
  
  doc.save(`unpaid_students_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Unpaid students PDF downloaded', 'success');
}

function navigateToView(view) {
  pushNavigationState();
  state.currentView = view;
  state.searchQuery = "";
  render();
}

// Debounce timer for search
let searchDebounceTimer = null;

function handleSearch(val) {
  state.searchQuery = val;

  // Clear previous debounce timer
  if (searchDebounceTimer) {
    clearTimeout(searchDebounceTimer);
  }

  // Debounce search update to prevent page shaking
  searchDebounceTimer = setTimeout(() => {
    // Update the view content with filtered results
    const { role } = state.currentUser || { role: 'guest' };
    const view = state.currentView;

    // Get current page content with filtering applied
    const page = renderPageByRole(role, view);
    const contentEl = document.getElementById('viewContent');

    if (contentEl) {
      // Preserve focus and cursor position
      const activeElement = document.activeElement;
      const isSearchInput = activeElement && activeElement.id === 'mainSearchInput';
      const cursorPos = isSearchInput ? activeElement.selectionStart : 0;

      // Update content
      contentEl.innerHTML = page.content;
      bindPageActions();

      // Restore focus and cursor position
      if (isSearchInput) {
        setTimeout(() => {
          const input = document.getElementById('mainSearchInput');
          if (input) {
            input.focus();
            input.setSelectionRange(cursorPos, cursorPos);
          }
        }, 0);
      }
    }
  }, 300); // 300ms debounce delay
}

// Make handleSearch globally available
window.handleSearch = handleSearch;

function handleNotifSearch(val) {
  state.notificationFilter = val;
  const { role } = state.currentUser || { role: 'guest' };
  const page = renderPageByRole(role, "notifications");
  
  const contentEl = document.getElementById('viewContent');
  if (contentEl) {
    const activeEl = document.activeElement;
    const isSearchInput = activeEl && activeEl.id === 'notifSearch';
    const isClearBtn = activeEl && (activeEl.closest('.btn-search-clear'));
    
    contentEl.innerHTML = page.content;
    bindPageActions();
    
    if (isSearchInput || isClearBtn) {
      setTimeout(() => {
        const input = document.getElementById('notifSearch');
        if (input) input.focus();
      }, 0);
    }
  } else {
    render();
  }
}

function downloadFocalListPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  const startY = addPDFHeader(doc, 'University Focal Persons List');
  
  const tableData = state.focalPersons.map(fp => [fp.id, fp.name, fp.route]);
  doc.autoTable({
    startY: startY,
    head: [['ID', 'Name', 'Route Assigned']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillColor: [62, 13, 72] }
  });
  doc.save(`focal_list_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Focal List PDF exported successfully', 'success');
}

function downloadFocalListCSV() {
  const headers = ['ID', 'Name', 'Route'];
  const rows = state.focalPersons.map(fp => [fp.id, fp.name, fp.route]);
  const content = [headers, ...rows].map(e => e.join(",")).join("\n");
  const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.setAttribute("href", url);
  link.setAttribute("download", `focal_list_${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast('Focal List CSV exported successfully', 'success');
}

function handleContactSubmit(event) {
  event.preventDefault();
  
  const formData = new FormData(event.target);
  const name = formData.get('name');
  const email = formData.get('email');
  const message = formData.get('message');
  
  // In a real application, this would send the data to a server
  // For now, we'll show a success message and clear the form
  showToast(`Thank you ${name}! Your message has been sent successfully.`, 'success');
  
  // Clear the form
  document.getElementById('contactForm').reset();
  
  // Log the message (in a real app, this would be sent to a server)
  console.log('Contact Form Submission:', { name, email, message, timestamp: new Date().toISOString() });
}

function sendReminderToStudent(studentId, studentName) {
  const defaultMessage = `Dear ${studentName},\n\nThis is a friendly reminder that your transport fee is due. Please ensure timely payment to avoid any disruption in transport services.\n\nThank you,\nSuperior University Transport Department`;
  
  showToast(`Sending reminder to ${studentName}...`, 'success');
  
  // In a real application, this would send an email/SMS
  // For now, we'll simulate the sending process
  setTimeout(() => {
    showToast(`Reminder sent to ${studentName}!`, 'success');
    console.log('Reminder sent to:', { studentId, studentName, message: defaultMessage, timestamp: new Date().toISOString() });
  }, 1500);
}


  // In a real application, this would send emails/SMS to all unpaid students
  // For now, we'll simulate the sending process
  setTimeout(() => {
    showToast(`Reminders sent to ${unpaidStudents.length} students successfully!`, 'success');
    console.log('Bulk reminders sent to:', unpaidStudents.map(s => ({ id: s.id, name: s.name })), 'timestamp:', new Date().toISOString());
  }, 2000);


function downloadBusesPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  const startY = addPDFHeader(doc, 'University Bus Fleet List');
  
  const tableData = state.buses.map(b => [b.id, b.model, b.capacity, b.route]);
  doc.autoTable({
    startY: startY,
    head: [['Bus ID', 'Bus Model', 'Capacity', 'Assigned Route']],
    body: tableData,
    theme: 'striped',
    headStyles: { fillColor: [62, 13, 72] }
  });
  doc.save(`bus_list_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Bus list PDF exported', 'success');
}

function downloadBusesCSV() {
  const headers = ['Bus ID', 'Model', 'Capacity', 'Route'];
  const rows = state.buses.map(b => [b.id, b.model, b.capacity, b.route]);
  const content = [headers, ...rows].map(e => e.join(",")).join("\n");
  const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.setAttribute("href", url);
  link.setAttribute("download", `bus_list_${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast('Bus list CSV exported', 'success');
}

function downloadRoutesPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  const startY = addPDFHeader(doc, 'University Transport Routes');
  
  const tableData = state.routes.map(r => [r.id, r.name, r.stops.length]);
  doc.autoTable({
    startY: startY,
    head: [['Route ID', 'Route Name', 'Total Stops']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillColor: [62, 13, 72] }
  });
  doc.save(`route_list_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Route list PDF exported', 'success');
}

function downloadRoutesCSV() {
  const headers = ['Route ID', 'Route Name', 'Total Stops'];
  const rows = state.routes.map(r => [r.id, r.name, r.stops.length]);
  const content = [headers, ...rows].map(e => e.join(",")).join("\n");
  const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.setAttribute("href", url);
  link.setAttribute("download", `route_list_${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast('Route list CSV exported', 'success');
}

function handleAccountsSearch(event) {
  const query = event.target.value;
  state.accountsSearchQuery = query;
  render();
}

function clearAccountsSearch() {
  state.accountsSearchQuery = "";
  render();
}

function togglePaymentStatus(studentId) {
  const student = state.students.find(s => s.id === studentId);
  if (student) {
    const previousStatus = student.feeStatus;
    student.feeStatus = student.feeStatus === "Paid" ? "Unpaid" : "Paid";
    student.lastPaymentDate = student.feeStatus === "Paid" ? new Date().toISOString().split('T')[0] : "-";
    
    saveState();
    render();
  }
}

// Enhanced payment processing functions
function receivePayment(studentId) {
  const student = state.students.find(s => s.id === studentId);
  if (student) {
    if (student.feeStatus === "Paid") {
      return;
    }
    
    student.feeStatus = "Paid";
    student.lastPaymentDate = new Date().toISOString().split('T')[0];
    saveState();
    render();
  }
}

function undoPayment(studentId) {
  const student = state.students.find(s => s.id === studentId);
  if (student) {
    if (student.feeStatus === "Unpaid") {
      return;
    }
    
    student.feeStatus = "Unpaid";
    student.lastPaymentDate = "-";
    saveState();
    render();
  }
}

function showPaymentDetails(studentId) {
  const student = state.students.find(s => s.id === studentId);
  if (!student) return;
  
  const route = routeById(student.route);
  const attendance = state.attendance.filter(a => a.studentId === student.id);
  const presentDays = attendance.filter(a => a.status === "Present").length;
  const totalDays = attendance.length;
  
  openModal(
    `Payment Details - ${student.name}`,
    `
      <div class="payment-details-modal">
        <div class="student-header">
          <div class="student-info">
            <h3>${student.name}</h3>
            <p><strong>ID:</strong> ${student.id}</p>
            <p><strong>Route:</strong> ${route ? route.name : student.route}</p>
          </div>
          <div class="payment-status">
            <span class="status-badge ${student.feeStatus === 'Paid' ? 'paid' : 'unpaid'}">
              ${student.feeStatus}
            </span>
          </div>
        </div>
        
        <div class="payment-info-grid">
          <div class="info-item">
            <label>Fee Amount</label>
            <strong>${Number(student.feeAmount).toLocaleString()} PKR</strong>
          </div>
          <div class="info-item">
            <label>Last Payment Date</label>
            <strong>${student.lastPaymentDate || 'Not paid yet'}</strong>
          </div>
          <div class="info-item">
            <label>Current Status</label>
            <strong class="${student.feeStatus === 'Paid' ? 'status-paid' : 'status-unpaid'}">${student.feeStatus}</strong>
          </div>
          <div class="info-item">
            <label>Attendance Rate</label>
            <strong>${totalDays > 0 ? Math.round((presentDays / totalDays) * 100) : 0}%</strong>
          </div>
        </div>
        
        <div class="action-section">
          ${student.feeStatus === 'Unpaid' ? `
            <button class="btn-primary" onclick="receivePayment('${student.id}'); closeModal();">
              <i class="fas fa-check-circle"></i> Receive Payment
            </button>
          ` : `
            <button class="btn-soft" onclick="undoPayment('${student.id}'); closeModal();">
              <i class="fas fa-undo"></i> Undo Payment
            </button>
          `}
          <button class="btn-soft" onclick="closeModal()">Close</button>
        </div>
      </div>
    `
  );
}

function viewBusDetails(busId) {
  const bus = state.buses.find(b => b.id === busId);
  if (!bus) return;
  
  const route = routeById(bus.route);
  const focalPerson = state.focalPersons.find(fp => fp.route === bus.route);
  
  openModal(
    `Bus Details - ${busId}`,
    `
      <div class="bus-details-modal">
        <div class="bus-header">
          <h3>${bus.id}</h3>
          <span class="bus-status-badge assigned">ASSIGNED TO YOU</span>
        </div>
        
        <div class="bus-info-grid">
          <div class="info-item">
            <label>Model</label>
            <strong>${bus.model}</strong>
          </div>
          <div class="info-item">
            <label>Capacity</label>
            <strong>${bus.capacity} students</strong>
          </div>
          <div class="info-item">
            <label>Route</label>
            <strong>${bus.route} - ${route ? route.name : 'Unknown'}</strong>
          </div>
          <div class="info-item">
            <label>Assigned Focal Person</label>
            <strong>${focalPerson ? focalPerson.name : 'Not assigned'}</strong>
          </div>
        </div>
        
        ${route ? `
          <div class="route-stops-section">
            <h4>Route Stops (${route.stops.length})</h4>
            <div class="stops-list">
              ${route.stops.map((stop, index) => `
                <div class="stop-item">
                  <span class="stop-number">${index + 1}</span>
                  <span class="stop-name">${stop}</span>
                </div>
              `).join('')}
            </div>
          </div>
        ` : ''}
        
        <div class="modal-actions">
          <button class="btn-primary" onclick="closeModal()">Close</button>
        </div>
      </div>
    `
  );
}

function downloadPaymentsPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  const startY = addPDFHeader(doc, 'Processed Payments Report');
  
  const totalStudents = state.students.length;
  const paidStudents = state.students.filter(s => s.feeStatus === 'Paid');
  const unpaidStudents = state.students.filter(s => s.feeStatus === 'Unpaid');
  const totalCollected = paidStudents.reduce((sum, s) => sum + Number(s.feeAmount), 0);
  
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text(`Total Students: ${totalStudents}`, 20, startY);
  doc.text(`Paid Students: ${paidStudents.length}`, 20, startY + 7);
  doc.text(`Unpaid Students: ${unpaidStudents.length}`, 20, startY + 14);
  doc.text(`Total Collected: PKR ${totalCollected.toLocaleString()}`, 20, startY + 21);
  
  const tableData = state.students.map(s => [
    s.id,
    s.name,
    studentRouteName(s.route),
    `PKR ${Number(s.feeAmount).toLocaleString()}`,
    s.feeStatus
  ]);
  
  doc.autoTable({
    startY: startY + 30,
    head: [['ID', 'Name', 'Route', 'Amount', 'Status']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillStyle: 'fill', fillColor: [62, 13, 72], textColor: [255, 255, 255], fontStyle: 'bold' },
    alternateRowStyles: { fillColor: [248, 240, 255] }
  });
  
  doc.save(`processed_payments_${new Date().toISOString().split('T')[0]}.pdf`);
  showToast('Payments PDF downloaded', 'success');
}

function downloadPaymentsExcel() {
  let csvContent = "Student ID,Name,Route,Amount,Status,Last Payment Date\n";
  
  state.students.forEach(student => {
    csvContent += `${student.id},"${student.name}",${student.route},${student.feeAmount},${student.feeStatus},${student.lastPaymentDate}\n`;
  });
  
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `processed_payments_${new Date().toISOString().split('T')[0]}.csv`;
  a.click();
  window.URL.revokeObjectURL(url);
  
  showToast('Payments Excel file downloaded', 'success');
}

function confirmDelete(callback, customMessage = null) {
  const message = customMessage || "Are you sure you want to delete this item?";
  
  openModal(
    "Confirm Deletion",
    `
      <div class="delete-confirmation">
        <div class="warning-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="warning-message">
          <h4>Delete Confirmation</h4>
          <p>${message.replace(/\n/g, '<br>')}</p>
        </div>
        <div class="confirmation-actions">
          <button class="btn-danger" onclick="executeDelete();">
            <i class="fas fa-trash"></i> Yes, Delete
          </button>
          <button class="btn-soft" onclick="closeModal(); clearDeleteCallback();">
            <i class="fas fa-times"></i> Cancel
          </button>
        </div>
      </div>
    `,
    true
  );
  window._deleteCallback = callback;
}

function executeDelete() {
  if (window._deleteCallback) {
    const callback = window._deleteCallback;
    closeModal();
    callback();
    clearDeleteCallback();
  }
}

function closeModal() {
  modalBackdrop.classList.add("hidden");
}

function clearDeleteCallback() {
  window._deleteCallback = null;
}

function setupDeleteEventListeners() {
  // Bus Management Delete Buttons
  document.querySelectorAll("[data-bus-delete]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const busId = btn.dataset.busDelete;
      const bus = state.buses.find(b => b.id === busId);
      
      if (!bus) {
        openModal("Error", "Bus not found. Please refresh the page and try again.");
        return;
      }
      
      // Check if any students are assigned to this bus
      const assignedStudents = state.students.filter(s => {
        const studentBus = busByRoute(s.route);
        return studentBus && studentBus.id === busId;
      });
      
      const confirmMessage = assignedStudents.length > 0 
        ? `Are you sure you want to delete bus ${busId}? This bus has ${assignedStudents.length} student(s) assigned to it. Those students will need to be reassigned to another bus.`
        : `Are you sure you want to delete bus ${busId} (${bus.model})?`;
      
      confirmDelete(() => {
        // Remove bus from state immediately
        state.buses = state.buses.filter((b) => b.id !== busId);
        
        // Save state first
        saveState();
        
        // Close the confirmation modal
        closeModal();
        
        // Render the updated interface
        render();
        
        // Show success feedback after render
        setTimeout(() => {
          openModal("Bus Deleted", `Bus ${busId} has been successfully deleted from the system.`);
        }, 100);
      }, confirmMessage);
    });
  });

  // Route Management Delete Buttons
  document.querySelectorAll("[data-route-delete]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const routeId = btn.dataset.routeDelete;
      const route = state.routes.find(r => r.id === routeId);
      
      if (!route) {
        openModal("Error", "Route not found. Please refresh the page and try again.");
        return;
      }
      
      // Check dependencies
      const assignedStudents = state.students.filter(s => s.route === routeId);
      const assignedBuses = state.buses.filter(b => b.route === routeId);
      const assignedFocalPersons = state.focalPersons.filter(fp => fp.route === routeId);
      
      let dependencyMessage = "";
      if (assignedStudents.length > 0 || assignedBuses.length > 0 || assignedFocalPersons.length > 0) {
        dependencyMessage = "\n\nWARNING: This route has dependencies:\n";
        if (assignedStudents.length > 0) {
          dependencyMessage += `\n\u2022 ${assignedStudents.length} student(s) assigned`;
        }
        if (assignedBuses.length > 0) {
          dependencyMessage += `\n\u2022 ${assignedBuses.length} bus(es) assigned`;
        }
        if (assignedFocalPersons.length > 0) {
          dependencyMessage += `\n\u2022 ${assignedFocalPersons.length} focal person(s) assigned`;
        }
        dependencyMessage += "\n\nDeleting this route will also remove these assignments.";
      }
      
      const confirmMessage = `Are you sure you want to delete route ${routeId} (${route.name})?${dependencyMessage}`;
      
      confirmDelete(() => {
        // Remove route and clean up dependencies immediately
        state.routes = state.routes.filter((r) => r.id !== routeId);
        
        // Update students assigned to this route
        state.students.forEach(s => {
          if (s.route === routeId) {
            s.route = "";
          }
        });
        
        // Update buses assigned to this route
        state.buses.forEach(b => {
          if (b.route === routeId) {
            b.route = "";
          }
        });
        
        // Update focal persons assigned to this route
        state.focalPersons.forEach(fp => {
          if (fp.route === routeId) {
            fp.route = "";
          }
        });
        
        // Save state first
        saveState();
        
        // Close the confirmation modal
        closeModal();
        
        // Render the updated interface
        render();
        
        // Show success feedback after render
        setTimeout(() => {
          openModal("Route Deleted", `Route ${routeId} (${route.name}) has been successfully deleted from the system.`);
        }, 100);
      }, confirmMessage);
    });
  });

  // Focal Person Management Delete Buttons
  document.querySelectorAll("[data-focal-delete]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const focalId = btn.dataset.focalDelete;
      const focalPerson = state.focalPersons.find(fp => fp.id === focalId);
      
      if (!focalPerson) {
        openModal("Error", "Focal person not found. Please refresh the page and try again.");
        return;
      }
      
      // Check if any buses are assigned to this focal person's route
      const assignedBuses = state.buses.filter(b => b.route === focalPerson.route);
      const assignedStudents = state.students.filter(s => s.route === focalPerson.route);
      
      let dependencyMessage = "";
      if (assignedBuses.length > 0 || assignedStudents.length > 0) {
        dependencyMessage = "\n\nWARNING: This focal person has responsibilities:\n";
        if (assignedBuses.length > 0) {
          dependencyMessage += `\n\u2022 ${assignedBuses.length} bus(es) on route ${focalPerson.route}`;
        }
        if (assignedStudents.length > 0) {
          dependencyMessage += `\n\u2022 ${assignedStudents.length} student(s) on route ${focalPerson.route}`;
        }
        dependencyMessage += "\n\nConsider assigning a new focal person to this route.";
      }
      
      const confirmMessage = `Are you sure you want to delete focal person ${focalPerson.name} (${focalId})?${dependencyMessage}`;
      
      confirmDelete(() => {
        // Remove focal person from state immediately
        state.focalPersons = state.focalPersons.filter((fp) => fp.id !== focalId);
        
        // Save state first
        saveState();
        
        // Close the confirmation modal
        closeModal();
        
        // Render the updated interface
        render();
        
        // Show success feedback after render
        setTimeout(() => {
          openModal("Focal Person Deleted", `Focal person ${focalPerson.name} (${focalId}) has been successfully removed from the system.`);
        }, 100);
      }, confirmMessage);
    });
  });
}

function renderRegistrationRequests() {
  const pendingRequests = state.registrationRequests.filter(req => req.status === 'pending');
  const approvedRequests = state.registrationRequests.filter(req => req.status === 'approved');
  const rejectedRequests = state.registrationRequests.filter(req => req.status === 'rejected');

  return `
    <div class="grid">
      <div class="card span-12">
        <h3>Pending Registration Requests (${pendingRequests.length})</h3>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Student ID</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            ${pendingRequests.map(req => `
              <tr>
                <td>${req.name}</td>
                <td>${req.username}</td>
                <td>${req.email}</td>
                <td>${roleLabel(req.role)}</td>
                <td>${req.studentId || '-'}</td>
                <td>${req.createdAt}</td>
                <td>
                  <button class="btn-att-paid" onclick="approveRequest('${req.id}')">
                    ✓ Approve
                  </button>
                  <button class="btn-att-absent" onclick="rejectRequest('${req.id}')">
                    ✗ Reject
                  </button>
                </td>
              </tr>
            `).join('') || '<tr><td colspan="7">No pending requests.</td></tr>'}
          </tbody>
        </table>
      </div>

      <div class="card span-6">
        <h3>Approved Requests (${approvedRequests.length})</h3>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Role</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            ${approvedRequests.map(req => `
              <tr>
                <td>${req.name}</td>
                <td>${roleLabel(req.role)}</td>
                <td>${req.createdAt}</td>
              </tr>
            `).join('') || '<tr><td colspan="3">No approved requests.</td></tr>'}
          </tbody>
        </table>
      </div>

      <div class="card span-6">
        <h3>Rejected Requests (${rejectedRequests.length})</h3>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Role</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            ${rejectedRequests.map(req => `
              <tr>
                <td>${req.name}</td>
                <td>${roleLabel(req.role)}</td>
                <td>${req.createdAt}</td>
              </tr>
            `).join('') || '<tr><td colspan="3">No rejected requests.</td></tr>'}
          </tbody>
        </table>
      </div>
    </div>
  `;
}

function approveRequest(requestId) {
  const request = state.registrationRequests.find(req => req.id === requestId);
  if (request) {
    request.status = 'approved';
    saveState();
    showToast(`Registration request for ${request.name} approved!`, 'success');
    render();
  }
}

function rejectRequest(requestId) {
  const request = state.registrationRequests.find(req => req.id === requestId);
  if (request) {
    request.status = 'rejected';
    saveState();
    showToast(`Registration request for ${request.name} rejected.`, 'info');
    render();
  }
}

render();
