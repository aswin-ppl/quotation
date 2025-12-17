    <button class="btn btn-danger p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="icon ti ti-settings fs-7"></i>
    </button>

    <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
            <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                Settings
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-simplebar style="height: calc(100vh - 80px)">
            <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="light-layout">
                    <i class="icon ti ti-brightness-up fs-7 me-2" id="theme-icon-sun"></i>Light
                </label>

                <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="dark-layout">
                    <i class="icon ti ti-moon fs-7 me-2" id="theme-icon-moon"></i>Dark
                </label>
            </div>

            <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="ltr-layout">
                    <i class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR
                </label>

                <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="rtl-layout">
                    <i class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL
                </label>
            </div>

            <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

            <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
                <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="BLUE_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="AQUA_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="PURPLE_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="Green_Theme"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Green_Theme')" for="Green_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="GREEN_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="Cyan_Theme"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Cyan_Theme')" for="Cyan_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="CYAN_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="Orange_Theme"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Orange_Theme')" for="Orange_Theme" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="ORANGE_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>
            </div>

            <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <div>
                    <input type="radio" class="btn-check" name="page-layout" id="vertical-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary rounded-2" for="vertical-layout">
                        <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
                    </label>
                </div>
                <div>
                    <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary rounded-2" for="horizontal-layout">
                        <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
                    </label>
                </div>
            </div>

            <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="boxed-layout">
                    <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed
                </label>

                <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="full-layout">
                    <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full
                </label>
            </div>

            <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <a href="javascript:void(0)" class="fullsidebar">
                    <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary rounded-2" for="full-sidebar">
                        <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full
                    </label>
                </a>
                <div>
                    <input type="radio" class="btn-check" name="sidebar-type" id="mini-sidebar"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary rounded-2" for="mini-sidebar">
                        <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse
                    </label>
                </div>
            </div>

            <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

            <div class="d-flex flex-row gap-3 customizer-box" role="group">
                <input type="radio" class="btn-check" name="card-layout" id="card-with-border"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="card-with-border">
                    <i class="icon ti ti-border-outer fs-7 me-2"></i>Border
                </label>

                <input type="radio" class="btn-check" name="card-layout" id="card-without-border"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2" for="card-without-border">
                    <i class="icon ti ti-border-none fs-7 me-2"></i>Shadow
                </label>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const byId = id => document.getElementById(id);
            const setAttr = (k, v) => document.documentElement.setAttribute(k, v);
            const save = (k, v) => localStorage.setItem(k, v);

            // Theme elements
            const lightInput = byId('light-layout');
            const darkInput = byId('dark-layout');
            const themeSun = byId('theme-icon-sun');
            const themeMoon = byId('theme-icon-moon');

            function applyTheme(theme) {
                setAttr('data-bs-theme', theme);
                save('theme', theme);
                if (theme === 'dark') {
                    if (darkInput) darkInput.checked = true;
                    if (lightInput) lightInput.checked = false;
                    if (themeMoon) themeMoon.style.color = 'var(--bs-primary)';
                    if (themeSun) themeSun.style.color = 'unset';
                } else {
                    if (lightInput) lightInput.checked = true;
                    if (darkInput) darkInput.checked = false;
                    if (themeSun) themeSun.style.color = 'var(--bs-primary)';
                    if (themeMoon) themeMoon.style.color = 'unset';
                }
            }

            if (lightInput) lightInput.addEventListener('change', () => applyTheme('light'));
            if (darkInput) darkInput.addEventListener('change', () => applyTheme('dark'));

            // Direction (ltr / rtl)
            const ltrInput = byId('ltr-layout');
            const rtlInput = byId('rtl-layout');
            function applyDirection(dir) {
                document.documentElement.setAttribute('dir', dir);
                save('direction', dir);
                if (dir === 'rtl') {
                    if (rtlInput) rtlInput.checked = true;
                    if (ltrInput) ltrInput.checked = false;
                } else {
                    if (ltrInput) ltrInput.checked = true;
                    if (rtlInput) rtlInput.checked = false;
                }
            }
            if (ltrInput) ltrInput.addEventListener('change', () => applyDirection('ltr'));
            if (rtlInput) rtlInput.addEventListener('change', () => applyDirection('rtl'));

            // Color theme (skin)
            const colorInputs = document.querySelectorAll("input[name='color-theme-layout']");
            function applyColorTheme(key) {
                if (!key) return;
                setAttr('data-color-theme', key);
                save('data-color-theme', key);
                // Uncheck all color inputs first
                colorInputs.forEach(i => i.checked = false);
                // Then check the selected one
                const inp = byId(key);
                if (inp) inp.checked = true;
            }
            colorInputs.forEach(i => i.addEventListener('change', function() {
                if (this.checked) applyColorTheme(this.id);
            }));

            // Page layout (vertical / horizontal) — uses data-layout
            const verInput = byId('vertical-layout');
            const horInput = byId('horizontal-layout');
            function applyPageLayout(val) {
                setAttr('data-layout', val);
                save('data-layout', val);
                if (val === 'horizontal') {
                    if (horInput) horInput.checked = true;
                    if (verInput) verInput.checked = false;
                } else {
                    if (verInput) verInput.checked = true;
                    if (horInput) horInput.checked = false;
                }
            }
            if (verInput) verInput.addEventListener('change', () => applyPageLayout('vertical'));
            if (horInput) horInput.addEventListener('change', () => applyPageLayout('horizontal'));

            // Container option (boxed / full)
            const boxedInput = byId('boxed-layout');
            const fullInput = byId('full-layout');
            function applyContainer(val) {
                setAttr('data-container', val);
                save('data-container', val);
                if (val === 'boxed') {
                    if (boxedInput) boxedInput.checked = true;
                    if (fullInput) fullInput.checked = false;
                } else {
                    if (fullInput) fullInput.checked = true;
                    if (boxedInput) boxedInput.checked = false;
                }
            }
            if (boxedInput) boxedInput.addEventListener('change', () => applyContainer('boxed'));
            if (fullInput) fullInput.addEventListener('change', () => applyContainer('full'));

            // Sidebar type (full / mini)
            const fullSidebar = byId('full-sidebar');
            const miniSidebar = byId('mini-sidebar');
            function applySidebar(val) {
                setAttr('data-sidebar', val);
                save('sidebar-type', val);
                if (val === 'mini') {
                    if (miniSidebar) miniSidebar.checked = true;
                    if (fullSidebar) fullSidebar.checked = false;
                } else {
                    if (fullSidebar) fullSidebar.checked = true;
                    if (miniSidebar) miniSidebar.checked = false;
                }
            }
            if (fullSidebar) fullSidebar.addEventListener('change', () => applySidebar('full'));
            if (miniSidebar) miniSidebar.addEventListener('change', () => applySidebar('mini'));

            // Card layout (border / shadow)
            const cardBorder = byId('card-with-border');
            const cardShadow = byId('card-without-border');
            function applyCard(val) {
                setAttr('data-card', val);
                save('card-layout', val);
                if (val === 'border') {
                    if (cardBorder) cardBorder.checked = true;
                    if (cardShadow) cardShadow.checked = false;
                } else {
                    if (cardShadow) cardShadow.checked = true;
                    if (cardBorder) cardBorder.checked = false;
                }
            }
            if (cardBorder) cardBorder.addEventListener('change', () => applyCard('border'));
            if (cardShadow) cardShadow.addEventListener('change', () => applyCard('shadow'));

            // Restore saved values on load
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            const savedDirection = localStorage.getItem('direction') || document.documentElement.getAttribute('dir') || 'ltr';
            applyDirection(savedDirection);

            const savedColor = localStorage.getItem('data-color-theme');
            if (savedColor) {
                setAttr('data-color-theme', savedColor);
                const colorInput = byId(savedColor);
                if (colorInput) colorInput.checked = true;
            }

            const savedPageLayout = localStorage.getItem('data-layout') || document.documentElement.getAttribute('data-layout');
            if (savedPageLayout) applyPageLayout(savedPageLayout);

            const savedContainer = localStorage.getItem('data-container');
            if (savedContainer) applyContainer(savedContainer);

            const savedSidebar = localStorage.getItem('sidebar-type');
            if (savedSidebar) applySidebar(savedSidebar);

            const savedCard = localStorage.getItem('card-layout');
            if (savedCard) applyCard(savedCard);

            // Expose handler for inline onclicks
            window.handleColorTheme = applyColorTheme;
        });
    </script>
