"use strict";

/* =====================================================
   HELPERS
===================================================== */
const $ = (s, c = document) => c.querySelector(s);
const $$ = (s, c = document) => c.querySelectorAll(s);

function initSwiper(selector, options) {
  if (!document.querySelector(selector)) return null;
  return new Swiper(selector, options);
}

function slideUp(target, duration = 500) {
  if (!target) return;
  target.style.transitionProperty = "height, margin, padding";
  target.style.transitionDuration = duration + "ms";
  target.style.boxSizing = "border-box";
  target.style.height = target.offsetHeight + "px";
  target.offsetHeight;
  target.style.overflow = "hidden";
  target.style.height = 0;
  setTimeout(() => {
    target.style.display = "none";
    target.style.removeProperty("height");
    target.style.removeProperty("overflow");
    target.style.removeProperty("transition-duration");
    target.style.removeProperty("transition-property");
  }, duration);
}

function slideDown(target, duration = 500) {
  if (!target) return;
  target.style.removeProperty("display");
  let display = window.getComputedStyle(target).display;
  if (display === "none") display = "block";
  target.style.display = display;
  const height = target.offsetHeight;
  target.style.overflow = "hidden";
  target.style.height = 0;
  target.offsetHeight;
  target.style.boxSizing = "border-box";
  target.style.transitionProperty = "height, margin, padding";
  target.style.transitionDuration = duration + "ms";
  target.style.height = height + "px";
  setTimeout(() => {
    target.style.removeProperty("height");
    target.style.removeProperty("overflow");
    target.style.removeProperty("transition-duration");
    target.style.removeProperty("transition-property");
  }, duration);
}

/* =====================================================
   INIT
===================================================== */
document.addEventListener("DOMContentLoaded", function () {

  /* ================= PRELOADER ================= */
  const preloader = $("#preloader");
  if (preloader) {
    window.addEventListener("load", () => preloader.classList.add("loaded"));
  }

  /* ================= SCROLL TOP ================= */
  const scrollTopBtn = $("#scroll__top");
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener("click", () =>
      window.scroll({ top: 0, behavior: "smooth" })
    );
    window.addEventListener("scroll", () => {
      scrollTopBtn.classList.toggle("active", window.scrollY > 300);
    });
  }

  /* ================= STICKY HEADER ================= */
  const header = $("header");
  const sticky = $(".header__sticky");
  if (header && sticky) {
    window.addEventListener("scroll", () => {
      sticky.classList.toggle(
        "sticky",
        window.scrollY > header.offsetTop
      );
    });
  }

  /* ================= SWIPERS ================= */
  initSwiper(".hero__slider--activation", {
    slidesPerView: 1,
    loop: true,
    pagination: { el: ".swiper-pagination", clickable: true },
  });

  initSwiper(".product__swiper--activation", {
    slidesPerView: 4,
    loop: true,
    spaceBetween: 30,
    breakpoints: {
      992: { slidesPerView: 4 },
      768: { slidesPerView: 3 },
      280: { slidesPerView: 2 },
      0: { slidesPerView: 1 },
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  initSwiper(".blog__swiper--activation", {
    slidesPerView: 3,
    loop: true,
    spaceBetween: 30,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  initSwiper(".instagram__swiper--activation", {
    slidesPerView: 7,
    loop: true,
    spaceBetween: 30,
    breakpoints: {
      1200: { slidesPerView: 6 },
      992: { slidesPerView: 5 },
      768: { slidesPerView: 4 },
      576: { slidesPerView: 3 },
      0: { slidesPerView: 2 },
    },
  });

  /* ================= PRODUCT GALLERY ================= */
  const nav = $(".product__media--nav");
  const preview = $(".product__media--preview");

  if (nav && preview) {
    const thumbs = new Swiper(nav, {
      slidesPerView: 5,
      spaceBetween: 10,
      watchSlidesProgress: true,
    });

    new Swiper(preview, {
      slidesPerView: 1,
      thumbs: { swiper: thumbs },
    });
  }

  /* ================= ACCORDION ================= */
  $$(".accordion__items--button").forEach(btn => {
    btn.addEventListener("click", () => {
      const item = btn.closest(".accordion__items");
      const body = item.querySelector(".accordion__items--body");
      item.classList.toggle("active");
      item.classList.contains("active")
        ? slideDown(body)
        : slideUp(body);
    });
  });

  /* ================= QUANTITY ================= */
  $$(".quantity__box").forEach(box => {
    const input = $("input", box);
    $(".increase", box)?.addEventListener("click", () => input.value++);
    $(".decrease", box)?.addEventListener("click", () => {
      input.value = Math.max(1, input.value - 1);
    });
  });

  /* ================= LIGHTBOX ================= */
  if (window.GLightbox) {
    GLightbox({ loop: true });
  }

  /* ================= MODAL ================= */
  $$("[data-open]").forEach(btn => {
    btn.addEventListener("click", () => {
      $("#" + btn.dataset.open)?.classList.add("is-visible");
    });
  });

  $$("[data-close]").forEach(btn => {
    btn.addEventListener("click", () =>
      btn.closest(".modal")?.classList.remove("is-visible")
    );
  });

  /* ================= OFFCANVAS & SEARCH (data-offcanvas) ================= */
  const offcanvasEl = $(".offcanvas__header");
  const searchBoxEl = $(".predictive__search--box");
  const body = document.body;

  function closeOffcanvas() {
    offcanvasEl?.classList.remove("open");
    body.classList.remove("overlay__active");
  }
  function closeSearch() {
    searchBoxEl?.classList.remove("active");
    body.classList.remove("overlay__active");
  }
  function closeAllPanels() {
    closeOffcanvas();
    closeSearch();
  }

  document.addEventListener("click", (e) => {
    const target = e.target;
    if (!target || !target.closest) return;

    if (target.closest("[data-offcanvas]")) {
      if (target.closest(".offcanvas__header--menu__open--btn")) {
        e.preventDefault();
        offcanvasEl?.classList.add("open");
        body.classList.add("overlay__active");
        return;
      }
      if (target.closest(".offcanvas__close--btn")) {
        e.preventDefault();
        closeOffcanvas();
        return;
      }
      if (target.closest(".search__open--btn")) {
        e.preventDefault();
        searchBoxEl?.classList.add("active");
        body.classList.add("overlay__active");
        return;
      }
      if (target.closest(".predictive__search--close__btn")) {
        e.preventDefault();
        closeSearch();
        return;
      }
    }

    if (body.classList.contains("overlay__active")) {
      if (!target.closest(".offcanvas__header") && !target.closest(".predictive__search--box")) {
        closeAllPanels();
      }
    }
  });

  /* ================= CATEGORIES DROPDOWN ================= */
  const categoriesHeader = $(".categories__menu--header");
  const dropdownCategories = $(".dropdown__categories--menu");
  if (categoriesHeader && dropdownCategories) {
    categoriesHeader.addEventListener("click", (e) => {
      e.preventDefault();
      dropdownCategories.classList.toggle("active");
    });
  }

  /* ================= NEWSLETTER ================= */
  const popup = $(".newsletter__popup");
  if (popup && !localStorage.getItem("newsletter__show")) {
    setTimeout(() => popup.classList.add("newsletter__show"), 3000);
  }

});
