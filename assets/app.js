const content = document.getElementById("content");

async function loadPartial(url) {
  const res = await fetch(url);

  if (!res.ok) {
    content.innerHTML = `<p class="error">Failed to load: ${url}</p>`;
    return;
  }

  content.innerHTML = await res.text();
  wireUpDynamicButtons(); // re-attach events every time we load new HTML
}

function wireUpDynamicButtons() {
  // ===== DASHBOARD FILTERS =====
  document.querySelectorAll("[data-filter]").forEach(btn => {
    btn.addEventListener("click", () => {
      const filter = btn.getAttribute("data-filter");
      loadPartial(`partials/dashboard.php?filter=${encodeURIComponent(filter)}`);
    });
  
  const dashboardAddContactBtn = document.getElementById("dashboardAddContactBtn");
  if (dashboardAddContactBtn) {
    dashboardAddContactBtn.addEventListener("click", () => {
      loadPartial("partials/new_contact.php");
    });
  }

  });

  // ===== VIEW CONTACT =====
  document.querySelectorAll("[data-view-contact]").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-view-contact");
      loadPartial(`partials/contact_details.php?id=${encodeURIComponent(id)}`);
    });
  });

  // ===== USERS PAGE: OPEN NEW USER FORM =====
  const showNewUserFormBtn = document.getElementById("showNewUserFormBtn");
  if (showNewUserFormBtn) {
    showNewUserFormBtn.addEventListener("click", () => {
      loadPartial("partials/new_user.php");
    });
  }

  // ===== NEW USER FORM SUBMIT =====
  const newUserForm = document.getElementById("newUserForm");
  if (newUserForm) {
    newUserForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(newUserForm);

      const res = await fetch("api/users_create.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      const msg = document.getElementById("formMessage");

      if (!data.ok) {
        msg.textContent = data.error || "Failed to create user";
        msg.className = "error";
        return;
      }

      loadPartial("partials/users.php?user_created=1");

    });
  }

  // ===== NEW CONTACT FORM SUBMIT =====
  const newContactForm = document.getElementById("newContactForm");
  if (newContactForm) {
    newContactForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(newContactForm);

      const res = await fetch("api/contacts_create.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      const msg = document.getElementById("formMessage");

      if (!data.ok) {
        msg.textContent = data.error || "Failed to create contact";
        msg.className = "error";
        return;
      }

      loadPartial("partials/dashboard.php?contact_created=1");

    });
  }

  // ===== CONTACT DETAILS: ASSIGN TO ME =====
  const assignBtn = document.getElementById("assignToMeBtn");
  if (assignBtn) {
    assignBtn.addEventListener("click", async () => {
      const contactId = assignBtn.getAttribute("data-contact-id");

      const res = await fetch("api/contact_assign.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `contact_id=${encodeURIComponent(contactId)}`
      });

      const data = await res.json();
      if (!data.ok) {
        alert(data.error || "Failed to assign");
        return;
      }

      loadPartial(`partials/contact_details.php?id=${encodeURIComponent(contactId)}`);
    });
  }

  // ===== CONTACT DETAILS: SWITCH TYPE =====
  const switchBtn = document.getElementById("switchTypeBtn");
  if (switchBtn) {
    switchBtn.addEventListener("click", async () => {
      const contactId = switchBtn.getAttribute("data-contact-id");

      const res = await fetch("api/contact_switch_type.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `contact_id=${encodeURIComponent(contactId)}`
      });

      const data = await res.json();
      if (!data.ok) {
        alert(data.error || "Failed to switch type");
        return;
      }

      loadPartial(`partials/contact_details.php?id=${encodeURIComponent(contactId)}`);
    });
  }

  // ===== CONTACT DETAILS: ADD NOTE =====
  const noteForm = document.getElementById("noteForm");
  if (noteForm) {
    noteForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(noteForm);
      const contactId = formData.get("contact_id");

      const res = await fetch("api/notes_create.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      if (!data.ok) {
        alert(data.error || "Failed to add note");
        return;
      }

      loadPartial(`partials/contact_details.php?id=${encodeURIComponent(contactId)}`);
    });
  }
}

// ===== SIDEBAR NAV =====
document.querySelectorAll(".nav-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const page = btn.getAttribute("data-page");

    if (page === "dashboard") loadPartial("partials/dashboard.php");
    if (page === "new_contact") loadPartial("partials/new_contact.php");
    if (page === "users") loadPartial("partials/users.php");
  });
});

// ===== LOGOUT =====
document.getElementById("logoutBtn").addEventListener("click", async () => {
  await fetch("api/logout.php");
  window.location.href = "index.php";
});

// ===== INITIAL LOAD =====
loadPartial("partials/dashboard.php");
