document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initAnnouncement();
  initOfficeFilter();
  initCarousels();
  initForms();
  initGallery();
});

function initMobileNav() {
  const header = document.querySelector('.estatein-header');
  const toggle = document.querySelector('.estatein-header__toggle');
  const mobileNav = document.querySelector('.estatein-header__mobile-nav');
  if (!toggle || !mobileNav) return;

  function setMobileNavOffset() {
    // Use the sticky header's bottom edge so the fixed nav sits flush under it
    // (includes header height + announcement/admin-bar offset in the viewport).
    const top = header
      ? Math.ceil(header.getBoundingClientRect().bottom)
      : 72;
    mobileNav.style.setProperty('--estatein-mobile-nav-top', `${top}px`);
  }

  function closeMenu() {
    toggle.classList.remove('is-open');
    mobileNav.classList.remove('is-open');
    header?.classList.remove('is-menu-open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', () => {
    const isOpen = toggle.classList.toggle('is-open');
    setMobileNavOffset();
    mobileNav.classList.toggle('is-open', isOpen);
    header?.classList.toggle('is-menu-open', isOpen);
    toggle.setAttribute('aria-expanded', String(isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  mobileNav.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  window.addEventListener('resize', () => {
    if (mobileNav.classList.contains('is-open')) {
      setMobileNavOffset();
    }
  });
}

function initAnnouncement() {
  const bar = document.querySelector('.estatein-announcement');
  const closeBtn = bar?.querySelector('.estatein-announcement__close');
  if (!bar || !closeBtn) return;

  if (localStorage.getItem('estatein_announcement_dismissed')) {
    bar.classList.add('is-hidden');
    return;
  }

  closeBtn.addEventListener('click', () => {
    bar.classList.add('is-hidden');
    localStorage.setItem('estatein_announcement_dismissed', '1');
  });
}

function initOfficeFilter() {
  const container = document.querySelector('[data-office-filter]');
  if (!container) return;

  const buttons = container.querySelectorAll('[data-filter]');
  const cards = document.querySelectorAll('[data-office-type]');
  const grid = document.querySelector('[data-office-grid]');

  function updateGrid() {
    if (!grid) return;
    const visibleCount = Array.from(cards).filter((card) => !card.hidden).length;
    grid.classList.toggle('estatein-office-grid--single', visibleCount === 1);
  }

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const filter = btn.dataset.filter;
      buttons.forEach((b) => b.classList.toggle('estatein-tabs__btn--active', b === btn));
      cards.forEach((card) => {
        card.hidden = filter !== 'all' && card.dataset.officeType !== filter;
      });
      updateGrid();
    });
  });

  updateGrid();
}

function initCarousels() {
  document.querySelectorAll('[data-carousel]').forEach((el) => {
    const track = el.querySelector('.estatein-carousel__track');
    const section = el.parentElement;
    const prev = section?.querySelector('[data-carousel-prev]');
    const next = section?.querySelector('[data-carousel-next]');
    const countEl = section?.querySelector('[data-carousel-count]');
    if (!track) return;

    const slides = track.querySelectorAll('.estatein-carousel__slide');
    const total = slides.length;

    function slideWidth() {
      const slide = slides[0];
      if (!slide) return 0;
      const styles = window.getComputedStyle(track);
      const gap = parseFloat(styles.columnGap || styles.gap) || 20;
      return slide.getBoundingClientRect().width + gap;
    }

    function getLastVisibleIndex() {
      const trackRect = track.getBoundingClientRect();
      let lastIndex = 0;

      slides.forEach((slide, index) => {
        const slideRect = slide.getBoundingClientRect();
        const isVisible = slideRect.left < trackRect.right - 1 && slideRect.right > trackRect.left + 1;
        if (isVisible) {
          lastIndex = index;
        }
      });

      return lastIndex;
    }

    function atStart() {
      return track.scrollLeft <= 1;
    }

    function atEnd() {
      return track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
    }

    function update() {
      const lastVisible = getLastVisibleIndex() + 1;
      const shown = String(lastVisible).padStart(2, '0');
      const ofTotal = String(total).padStart(2, '0');

      if (countEl) {
        countEl.textContent = `${shown} of ${ofTotal}`;
      }

      if (prev) {
        prev.disabled = atStart();
      }

      if (next) {
        next.disabled = atEnd();
      }
    }

    function scrollByDir(dir) {
      if ((dir < 0 && atStart()) || (dir > 0 && atEnd())) {
        return;
      }
      track.scrollBy({ left: dir * slideWidth(), behavior: 'smooth' });
    }

    prev?.addEventListener('click', () => scrollByDir(-1));
    next?.addEventListener('click', () => scrollByDir(1));
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });
}

function initForms() {
  document.querySelectorAll('[data-estatein-form]').forEach((form) => {
    let clientError = form.querySelector('[data-form-client-error]');
    if (!clientError) {
      clientError = document.createElement('div');
      clientError.className = 'estatein-notice estatein-notice--error';
      clientError.setAttribute('data-form-client-error', '');
      clientError.setAttribute('role', 'alert');
      clientError.hidden = true;
      clientError.innerHTML = '<p></p>';
      form.prepend(clientError);
    }

    const showClientError = (message) => {
      clientError.querySelector('p').textContent = message;
      clientError.hidden = false;
    };

    const hideClientError = () => {
      clientError.hidden = true;
    };

    form.addEventListener('submit', (e) => {
      const honeypot = form.querySelector('.estatein-honeypot input');
      if (honeypot && honeypot.value) {
        e.preventDefault();
        return;
      }

      hideClientError();
      let valid = true;

      form.querySelectorAll('[required]').forEach((field) => {
        const empty = field.type === 'checkbox' ? !field.checked : !String(field.value).trim();
        if (empty) {
          valid = false;
          field.setAttribute('aria-invalid', 'true');
        } else {
          field.removeAttribute('aria-invalid');
        }
      });

      const emailField = form.querySelector('[name="email"]');
      if (emailField && emailField.value.trim() && !emailField.checkValidity()) {
        valid = false;
        emailField.setAttribute('aria-invalid', 'true');
      }

      if (!valid) {
        e.preventDefault();
        showClientError('Please complete all required fields before submitting.');
      }
    });
  });

  if (window.location.hash === '#contact-form') {
    document.getElementById('contact-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

function initGallery() {
  const gallery = document.querySelector('[data-property-gallery]');
  if (!gallery) return;

  const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');
  const mainImages = gallery.querySelectorAll('[data-gallery-main]');
  const prevBtn = gallery.querySelector('[data-gallery-prev]');
  const nextBtn = gallery.querySelector('[data-gallery-next]');
  const dots = gallery.querySelectorAll('[data-gallery-dot]');

  if (mainImages.length < 2) return;

  const urls = Array.from(thumbs, (thumb) => thumb.dataset.full || thumb.src);
  if (urls.length < 2) return;

  let startIndex = 0;
  const maxStart = urls.length - 2;

  const windowStartForThumb = (index) => {
    if (index <= 0) return 0;
    return Math.min(index - 1, maxStart);
  };

  const updateNav = () => {
    if (prevBtn) prevBtn.disabled = startIndex === 0;
    if (nextBtn) nextBtn.disabled = startIndex >= maxStart;
    dots.forEach((dot) => {
      const slideIndex = Number(dot.dataset.galleryDot);
      dot.classList.toggle('is-active', slideIndex === startIndex);
    });
  };

  const applyWindow = (nextStart, activeThumb = nextStart) => {
    if (nextStart < 0 || nextStart > maxStart) return;

    const changed = nextStart !== startIndex;
    startIndex = nextStart;

    if (changed) {
      gallery.classList.add('is-sliding');
      mainImages[0].src = urls[startIndex];
      mainImages[1].src = urls[startIndex + 1];

      window.setTimeout(() => {
        gallery.classList.remove('is-sliding');
      }, 300);
    }

    thumbs.forEach((t) => t.classList.remove('is-active'));
    if (thumbs[activeThumb]) {
      thumbs[activeThumb].classList.add('is-active');
    }

    updateNav();
  };

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener('click', () => {
      applyWindow(windowStartForThumb(index), index);
    });
  });

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      applyWindow(startIndex - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      applyWindow(startIndex + 1);
    });
  }

  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      applyWindow(Number(dot.dataset.galleryDot));
    });
  });

  updateNav();
}
