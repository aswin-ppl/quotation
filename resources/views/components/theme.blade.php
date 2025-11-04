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

                <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Green_Theme')" for="green-theme-layout" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="GREEN_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Cyan_Theme')" for="cyan-theme-layout" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="CYAN_THEME">
                    <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                        <i class="ti ti-check text-white d-flex icon fs-5"></i>
                    </div>
                </label>

                <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout"
                    autocomplete="off" />
                <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                    onclick="handleColorTheme('Orange_Theme')" for="orange-theme-layout" data-bs-toggle="tooltip"
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
            const moonBtn = document.querySelector(".dark-layout");
            const moonBtnIcon = document.querySelector(".dark-layout").querySelector("iconify-icon");
            const themeMoonBtn = document.querySelector("#theme-icon-moon");

            const sunBtn = document.querySelector(".light-layout");
            const sunBtnIcon = document.querySelector(".light-layout").querySelector("iconify-icon");
            const themeSunBtn = document.querySelector("#theme-icon-sun");

            const blueTheme = document.querySelector("label[for='Blue_Theme']");



            // Set theme + save in localStorage
            function setTheme(theme) {
                document.documentElement.setAttribute("data-bs-theme", theme);
                localStorage.setItem("theme", theme);

                if (theme === "dark") {
                    moonBtn.style.display = "none";
                    moonBtnIcon.style.display = "none";
                    themeMoonBtn.style.color = "var(--bs-primary)";
                    themeSunBtn.style.color = "unset";

                    sunBtn.style.display = "inline-flex";
                    sunBtnIcon.style.display = "flex";
                } else {
                    sunBtn.style.display = "none";
                    sunBtnIcon.style.display = "none";
                    themeSunBtn.style.color = "var(--bs-primary)";
                    themeMoonBtn.style.color = "unset";

                    moonBtn.style.display = "inline-flex";
                    moonBtnIcon.style.display = "flex";
                }
            }

            // Click handlers
            moonBtn.addEventListener("click", () => setTheme("dark"));
            sunBtn.addEventListener("click", () => setTheme("light"));
            
            blueTheme.addEventListener("click", () => function(){
                console.log('asas');
            });

            // On load — check saved theme
            const savedTheme = localStorage.getItem("theme") || "light";
            setTheme(savedTheme);
            document.documentElement.setAttribute("data-color-theme", localStorage.getItem("data-color-theme"));
            document.documentElement.setAttribute("data-layout", localStorage.getItem("data-layout"));
        });

        function handleColorTheme(e) {
            localStorage.setItem("data-color-theme", e);
            document.documentElement.setAttribute("data-color-theme", e);
        }

        // handle layout

        const horizontalBtn = document.querySelector("label[for='horizontal-layout']");
        const verticalBtn = document.querySelector("label[for='vertical-layout']");
        
        horizontalBtn.addEventListener("click", () => {
            localStorage.setItem("data-layout", "horizontal");
        });
        verticalBtn.addEventListener("click", () => {
            localStorage.setItem("data-layout", "vertical");
        });
    </script>
