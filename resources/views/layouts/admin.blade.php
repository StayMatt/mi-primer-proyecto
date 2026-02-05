<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlO MOO! - @yield('title')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        
        /* SIDEBAR */
        .sidebar {
            width: 260px; height: 100vh; background-color: #0b132b;
            position: fixed; top: 0; left: 0;
            display: flex; flex-direction: column; z-index: 1000;
        }

        .brand-logo {
            font-size: 1.4rem; font-weight: 700; color: white;
            padding: 25px 20px; display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu { flex-grow: 1; overflow-y: auto; padding-top: 10px; }

        .nav-link {
            color: #8d99ae; padding: 12px 25px; font-weight: 500;
            display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; transition: all 0.3s; border-left: 3px solid transparent;
        }
        .nav-link:hover { color: #edf2f4; background-color: rgba(255,255,255,0.05); }
        .nav-link.active { color: white; border-left-color: #4361ee; background-color: rgba(67, 97, 238, 0.1); }
        
        .nav-link i.icon-main { width: 25px; text-align: center; margin-right: 10px; }
        .nav-link i.icon-arrow { font-size: 0.8rem; transition: transform 0.3s; }
        .nav-link[aria-expanded="true"] .icon-arrow { transform: rotate(90deg); }

        .submenu { background-color: #060b19; }
        .submenu a {
            padding: 10px 20px 10px 65px; color: #6c757d; font-size: 0.9rem;
            display: block; text-decoration: none; transition: 0.2s;
        }
        .submenu a:hover { color: #4361ee; }
        .submenu a.active-sub { color: #4361ee !important; font-weight: 600; }

        .sidebar-footer {
            padding: 20px; background-color: #0b132b; border-top: 1px solid rgba(255,255,255,0.05);
        }
        .btn-logout {
            width: 100%; background-color: rgba(220, 53, 69, 0.1); color: #ef233c;
            border: 1px solid #ef233c; padding: 10px; border-radius: 8px; font-weight: 600;
            transition: all 0.3s; display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-logout:hover { background-color: #ef233c; color: white; }

        .main-content { margin-left: 260px; padding: 30px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand-logo">
            <i class="fas fa-cow fa-lg text-primary"></i> <span>ALO MOO!</span>
        </div>

        <div class="sidebar-menu">
            <nav class="nav flex-column">
                
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <div><i class="fas fa-border-all icon-main"></i> Inicio</div>
                </a>
                
               <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.index') ? 'active' : '' }}">
                 <div><i class="fas fa-utensils icon-main"></i> Vender</div>
                </a>

                <a href="{{ route('kitchen.index') }}" class="nav-link {{ request()->routeIs('kitchen.index') ? 'active' : '' }}">
                    <div><i class="fas fa-fire icon-main"></i> Cocina</div>
                </a>

                <div class="mt-4 mb-2 px-4 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 1px;">Gestión</div>

                @php $isSalesActive = request()->routeIs('sales.*'); @endphp
                <a href="#menuFinanzas" class="nav-link {{ $isSalesActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" aria-expanded="{{ $isSalesActive ? 'true' : 'false' }}">
                    <div><i class="fas fa-wallet icon-main"></i> Finanzas</div>
                    <i class="fas fa-chevron-right icon-arrow"></i>
                </a>
                <div class="collapse {{ $isSalesActive ? 'show' : '' }}" id="menuFinanzas">
                    <div class="submenu">
                        <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.index') ? 'active-sub' : '' }}">
                            Ventas y Folios
                        </a>
                        <a href="#">Gastos de Operación</a>
                        <a href="#">Corte de Caja</a>
                        <a href="#">Balance General</a>
                    </div>
                </div>

                @php $isStockActive = request()->routeIs('stock.*'); @endphp
                <a href="#menuInventario" class="nav-link {{ $isStockActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" aria-expanded="{{ $isStockActive ? 'true' : 'false' }}">
                    <div><i class="fas fa-boxes icon-main"></i> Inventario</div>
                    <i class="fas fa-chevron-right icon-arrow"></i>
                </a>
                <div class="collapse {{ $isStockActive ? 'show' : '' }}" id="menuInventario">
                    <div class="submenu">
                        <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.index') ? 'active-sub' : '' }}">
                            Stock Actual
                        </a>
                        
                        <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.index') ? 'active-sub' : '' }}">
                            Reabastecer
                        </a>
                    </div>
                </div>

                @php
                    // Activamos si estamos en categorías O en cualquier ruta de productos (inventario o cocina)
                    $isCatalogActive = request()->routeIs('categories.*') || request()->routeIs('products.*');
                @endphp

                <a href="#menuCatalogos" class="nav-link {{ $isCatalogActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" aria-expanded="{{ $isCatalogActive ? 'true' : 'false' }}">
                    <div><i class="fas fa-book icon-main"></i> Catálogos</div>
                    <i class="fas fa-chevron-right icon-arrow"></i>
                </a>
                <div class="collapse {{ $isCatalogActive ? 'show' : '' }}" id="menuCatalogos">
                    <div class="submenu">
                        <a href="{{ route('products.inventory') }}" class="{{ request()->routeIs('products.inventory') ? 'active-sub' : '' }}">
                            Productos (Gaseosas)
                        </a>
                        
                        <a href="{{ route('products.kitchen') }}" class="{{ request()->routeIs('products.kitchen') ? 'active-sub' : '' }}">
                            Preparados (Comida)
                        </a>
                        
                        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active-sub' : '' }}">
                            Categorías
                        </a>
                    </div>
                </div>

                <a href="#menuConfig" class="nav-link collapsed" data-bs-toggle="collapse">
                    <div><i class="fas fa-cog icon-main"></i> Configuración</div>
                    <i class="fas fa-chevron-right icon-arrow"></i>
                </a>
                <div class="collapse" id="menuConfig">
                    <div class="submenu">
                        <a href="#">Usuarios</a>
                        <a href="#">Ajustes Generales</a>
                    </div>
                </div>

            </nav>
        </div>

        <div class="sidebar-footer">
            <button class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Salir del Sistema
            </button>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>