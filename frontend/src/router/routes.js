import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";

const routes = [
  {
    path: "/sign-in",
    component: () => import("layouts/SignInLayout.vue"),

    children: [
      {
        path: "",
        component: () => import("pages/SignIn.vue"),
        name: "sign-in",
      },
    ],
  },
  {
    path: "/admin",
    component: () => import("layouts/AdminLayout.vue"),
    name: "admin",
    beforeEnter: async (to, from, next) => {
      const store = useStore();

      if (!store.token) {
        next({ name: "sign-in" });
        return;
      }

      const response = await callApi({
        path: "/verify-token",
        method: "get",
        useAuth: true,
      });

      if (!response.authorized) {
        store.token = null;
        next({ name: "sign-in" });
        return;
      }

      next();
    },
    children: [
      {
        path: "",
        component: () => import("pages/admin/AdminDashboard.vue"),
        name: "admin-dashboard",
        beforeEnter: async () => {
          const store = useStore();
          store.admin.dashboard = await callApi({
            path: "/admin/dashboard",
            method: "get",
            useAuth: true,
          });
        },
      },
      {
        path: "home",
        component: () => import("pages/admin/AdminHome.vue"),
        name: "admin-home",
        beforeEnter: async () => {
          const store = useStore();
          store.admin.home = await callApi({ path: "/home", method: "get" });
        },
      },
      {
        path: "news",
        component: () => import("pages/admin/AdminNews.vue"),
        name: "admin-news",
        beforeEnter: async () => {
          const store = useStore();
          store.admin.news = await callApi({ path: "/news", method: "get" });
        },
      },
      {
        path: "about",
        component: () => import("pages/admin/AdminAbout.vue"),
        beforeEnter: async () => {
          const store = useStore();
          store.admin.about = await callApi({
            path: "/about-me",
            method: "get",
          });
        },
        name: "admin-about",
      },
      {
        path: "calendar",
        component: () => import("pages/admin/AdminCalendar.vue"),
        beforeEnter: async () => {
          const store = useStore();

          store.admin.calendar = await callApi({
            path: "/calendar",
            method: "get",
          });
        },
        name: "admin-calendar",
      },
    ],
  },
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/IndexPage.vue"),
        beforeEnter: async () => {
          const store = useStore();

          store.home = await callApi({ path: "/home", method: "get" });
          store.news = await callApi({ path: "/news", method: "get" });
        },
        name: "home",
      },
      {
        path: "about",
        component: () => import("pages/AboutPage.vue"),
        beforeEnter: async () => {
          const store = useStore();
          store.about = await callApi({ path: "/about-me", method: "get" });
        },
        name: "about",
      },
      {
        path: "race-calendar",
        component: () => import("pages/RaceCalendar.vue"),
        beforeEnter: async () => {
          const store = useStore();

          store.calendar = await callApi({ path: "/calendar", method: "get" });
        },
        name: "race-calendar",
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
