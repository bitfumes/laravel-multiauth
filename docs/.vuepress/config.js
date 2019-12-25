module.exports = {
  markdown: {
    lineNumbers: true
  },
  title: "Bitfumes Multiauth",
  description: "Create Admin Authentication in just 5 minutes.",
  sidebar: true,
  searchPlaceholder: "Search...",
  themeConfig: {
    repo: "bitfumes/laravel-multiauth",
    nav: [
      {
        text: "Branch",
        ariaLabel: "Api Branch",
        items: [
          { text: "API", link: "/jwt-auth/installation" },
          { text: "Web", link: "/" }
        ]
      },
      {
        text: "Changelog",
        link: "/changelog"
      }
    ],
    sidebar: [
      {
        title: "Get Started", // required
        collapsable: false,
        children: ["/installation", "publish", "version-guidance"]
      },
      {
        title: "Settings",
        path: "/settings",
        collapsable: true
      },
      {
        title: "Role & Permissions",
        collapsable: false,
        children: ["roles", "permissions"]
      },
      {
        title: "Access Level",
        collapsable: false,
        children: ["admin-policy", "middleware", "blade"]
      },
      "/another-auth",
      {
        title: "JWT Api Version",
        collapsable: false,
        children: [
          ["jwt-auth/installation", "Installation"],
          ["jwt-auth/auth", "Authentication Endpoints"],
          ["jwt-auth/admins", "Admins Endpoints"],
          ["jwt-auth/roles", "Roles Endpoints"],
          ["jwt-auth/admin-role", "Admin Role Connection"],
          ["jwt-auth/permissions", "Permission Endpoints"]
        ]
      },
      "/support"
    ],
    repoLabel: "Github",
    docsDir: "docs",
    docsBranch: "master",
    editLinks: true,
    editLinkText: "Help us improve this page!",
    smoothScroll: true
  }
};
