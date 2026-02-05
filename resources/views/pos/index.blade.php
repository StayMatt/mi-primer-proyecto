@extends('layouts.admin')

@section('title', 'Terminal de Ventas')

@section('content')
<style>
    /* --- ESTILOS GENERALES --- */
    body { background-color: #f8f9fa; }
    
    /* Botones de Filtro (Categorías) */
    .cat-btn {
        border: none; background: white; color: #6c757d; font-weight: 500;
        border-radius: 50px; padding: 8px 20px; transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        white-space: nowrap;
    }
    .cat-btn:hover, .cat-btn.active {
        background: #4361ee; color: white; transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    /* Tarjetas de Producto */
    .pos-card {
        border: none; border-radius: 16px; background: white;
        transition: all 0.2s; cursor: pointer; position: relative; overflow: hidden;
        height: 100%; display: flex; flex-direction: column;
    }
    .pos-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    
    .stock-badge { 
        font-size: 0.7rem; padding: 4px 8px; border-radius: 6px; 
        background: #eef2ff; color: #4361ee; font-weight: 600; 
    }
    
    .btn-add {
        width: 35px; height: 35px; border-radius: 50%; background: #f8f9fa;
        border: none; color: #4361ee; display: flex; align-items: center; justify-content: center; transition: 0.2s;
    }
    .pos-card:hover .btn-add { background: #4361ee; color: white; }

    /* Sección Carrito */
    .cart-section {
        background: white; border-radius: 16px; height: calc(100vh - 100px);
        display: flex; flex-direction: column; box-shadow: -5px 0 20px rgba(0,0,0,0.02);
    }
    .cart-item { 
        background: #f8f9fa; border-radius: 12px; padding: 10px; 
        margin-bottom: 10px; border: 1px solid transparent; 
    }
    .cart-item:hover { border-color: #e2e8f0; }

    /* Modal Visual */
    .receipt-modal { font-family: 'Courier New', Courier, monospace; }
    .dashed-line { border-bottom: 2px dashed #000; margin: 10px 0; }
</style>

<div class="row h-100 g-4">
    
    <div class="col-md-8 d-flex flex-column h-100">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-3">Terminal de Ventas</h3>
            <div class="d-flex gap-2 overflow-auto pb-2">
                <button class="cat-btn active" onclick="filterCategory('all', this)">Todos</button>
                @foreach($categories as $category)
                    <button class="cat-btn" onclick="filterCategory('cat-{{ $category->id }}', this)">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex-grow-1 overflow-auto pe-2" style="max-height: calc(100vh - 200px);">
            <div class="row row-cols-2 row-cols-md-3 g-3">
                @foreach($categories as $category)
                    @foreach($category->products as $product)
                        <div class="col product-item cat-{{ $category->id }}">
                            
                            <div class="pos-card p-3" 
                                 onclick="selectProduct(this)" 
                                 data-id="{{ $product->id }}" 
                                 data-name="{{ $product->name }}" 
                                 data-price="{{ $product->price }}">
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $product->name }}</h6>
                                    @if($product->type == 'product')
                                        <span class="stock-badge">Stock: {{ $product->stock }}</span>
                                    @endif
                                </div>
                                
                                <div class="text-center my-3 flex-grow-1 d-flex align-items-center justify-content-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" style="height: 80px; object-fit: contain;">
                                    @else
                                        <i class="fas fa-utensils fa-3x text-light"></i>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                    <span class="h5 fw-bold text-primary mb-0">${{ number_format($product->price, 2) }}</span>
                                    <button class="btn-add"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="cart-section p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Carrito</h5>
                <span class="badge bg-light text-dark" id="cart-count">0 ITEMS</span>
            </div>

            <div class="flex-grow-1 overflow-auto mb-3" id="cart-container">
                <div class="text-center text-muted mt-5">
                    <i class="fas fa-basket-shopping fa-3x opacity-25 mb-3"></i>
                    <p>Su pedido está vacío</p>
                </div>
            </div>

            <div class="mt-auto">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold" id="cart-subtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h4 fw-bold">Total</span>
                    <span class="h4 fw-bold text-primary" id="cart-total">$0.00</span>
                </div>

                <button class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" onclick="showReceipt()">
                    Confirmar y Boleta
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body receipt-modal p-4">
                <div class="text-center mb-3">
                    <h6 class="fw-bold mb-1">ALO MOO! BURGERS</h6>
                    <small class="d-block text-muted">Av. Principal 123</small>
                    <small class="d-block mt-2" id="receipt-date">--/--/----</small>
                </div>

                <div class="dashed-line"></div>
                <div class="small fw-bold mb-2">ORDEN #<span id="order-folio">0000</span></div>
                
                <div id="receipt-items" class="small"></div> <div class="dashed-line"></div>

                <div class="d-flex justify-content-between small mb-1">
                    <span>Subtotal:</span> <span id="receipt-subtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span>IGV (18%):</span> <span id="receipt-tax">$0.00</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                    <span>TOTAL:</span> <span id="receipt-total">$0.00</span>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-dark btn-sm" onclick="printTicket()"><i class="fas fa-print me-2"></i>Imprimir</button>
                    <button class="btn btn-primary btn-sm" onclick="finishOrder()">Nueva Orden</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    // 1. FILTRADO VISUAL
    function filterCategory(catClass, btn) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        let items = document.querySelectorAll('.product-item');
        items.forEach(item => {
            if (catClass === 'all' || item.classList.contains(catClass)) item.style.display = 'block';
            else item.style.display = 'none';
        });
    }

    // 2. SELECCIONAR PRODUCTO (SOLUCIÓN AL ERROR DE VS CODE)
    // Esta función lee los atributos del HTML en lugar de recibir parámetros directos
    function selectProduct(element) {
        let id = element.getAttribute('data-id');
        let name = element.getAttribute('data-name');
        let price = parseFloat(element.getAttribute('data-price'));
        addToCart(id, name, price);
    }

    // 3. AGREGAR AL ARRAY
    function addToCart(id, name, price) {
        let existing = cart.find(item => item.id === id);
        if (existing) existing.qty++;
        else cart.push({ id: id, name: name, price: price, qty: 1 });
        renderCart();
    }

    // 4. DIBUJAR CARRITO
    function renderCart() {
        let container = document.getElementById('cart-container');
        let html = '';
        let total = 0;

        if (cart.length === 0) {
            container.innerHTML = '<div class="text-center text-muted mt-5"><i class="fas fa-basket-shopping fa-3x opacity-25 mb-3"></i><p>Su pedido está vacío</p></div>';
            updateTotals(0);
            return;
        }

        cart.forEach((item, index) => {
            total += item.price * item.qty;
            html += `
                <div class="cart-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">${item.name}</div>
                        <div class="text-muted small">$${item.price.toFixed(2)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-light border" onclick="updateQty(${index}, -1)">-</button>
                        <span class="small fw-bold">${item.qty}</span>
                        <button class="btn btn-sm btn-light border" onclick="updateQty(${index}, 1)">+</button>
                    </div>
                    <div class="fw-bold small">$${(item.price * item.qty).toFixed(2)}</div>
                    <button class="btn btn-sm text-danger" onclick="removeItem(${index})">&times;</button>
                </div>
            `;
        });

        container.innerHTML = html;
        updateTotals(total);
    }

    function updateQty(index, change) {
        cart[index].qty += change;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateTotals(total) {
        document.getElementById('cart-subtotal').innerText = '$' + (total / 1.18).toFixed(2);
        document.getElementById('cart-total').innerText = '$' + total.toFixed(2);
        document.getElementById('cart-count').innerText = cart.length + ' ITEMS';
    }

    // 5. MOSTRAR MODAL
    function showReceipt() {
        if (cart.length === 0) return alert('El carrito está vacío');

        let container = document.getElementById('receipt-items');
        let html = '';
        let total = 0;

        cart.forEach(item => {
            let subtotal = item.price * item.qty;
            total += subtotal;
            html += `
                <div class="d-flex justify-content-between mb-1">
                    <span>${item.qty}x ${item.name}</span>
                    <span>$${subtotal.toFixed(2)}</span>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('receipt-subtotal').innerText = '$' + (total / 1.18).toFixed(2);
        document.getElementById('receipt-tax').innerText = '$' + (total - (total / 1.18)).toFixed(2);
        document.getElementById('receipt-total').innerText = '$' + total.toFixed(2);
        
        let now = new Date();
        document.getElementById('receipt-date').innerText = now.toLocaleString();
        document.getElementById('order-folio').innerText = Math.floor(Math.random() * 10000);

        new bootstrap.Modal(document.getElementById('receiptModal')).show();
    }

    // 6. IMPRIMIR TICKET (SOLUCIÓN 80mm LIMPIA)
    function printTicket() {
        let date = document.getElementById('receipt-date').innerText;
        let folio = document.getElementById('order-folio').innerText;
        let subtotal = document.getElementById('receipt-subtotal').innerText;
        let tax = document.getElementById('receipt-tax').innerText;
        let total = document.getElementById('receipt-total').innerText;
        
        let itemsHtml = '';
        cart.forEach(item => {
            let itemSub = (item.price * item.qty).toFixed(2);
            itemsHtml += `
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>${item.qty} ${item.name}</span>
                    <span>$${itemSub}</span>
                </div>
            `;
        });

        // HTML PARA LA IMPRESORA
        let ticketContent = `
            <html>
            <head>
                <style>
                    @page { size: 80mm auto; margin: 0; }
                    body { 
                        width: 78mm; 
                        font-family: 'Courier New', monospace; 
                        font-size: 12px; 
                        margin: 0; padding: 5px; 
                    }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-bottom: 1px dashed #000; margin: 8px 0; }
                    .flex { display: flex; justify-content: space-between; }
                    .mt { margin-top: 5px; }
                </style>
            </head>
            <body>
                <div class="center bold" style="font-size: 14px;">ALO MOO!</div>
                <div class="center">Av. Principal 00000</div>
                <div class="center">RUC: 0000000</div>
                <div class="center mt">${date}</div>
                
                <div class="line"></div>
                <div class="bold">ORDEN #${folio}</div>
                <div class="mt">${itemsHtml}</div>
                <div class="line"></div>
                
                <div class="flex"><span>Subtotal:</span> <span>${subtotal}</span></div>
                <div class="flex"><span>IGV (18%):</span> <span>${tax}</span></div>
                <div class="flex bold mt" style="font-size: 16px;"><span>TOTAL:</span> <span>${total}</span></div>
                
                <div class="center mt" style="margin-top: 20px;">
                    ¡Gracias por su compra!<br>
                    Wifi: AloMoo_Free
                </div>
            </body>
            </html>
        `;

        var iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write(ticketContent);
        doc.close();

        setTimeout(function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 500);
    }

    function finishOrder() {
        cart = [];
        renderCart();
        bootstrap.Modal.getInstance(document.getElementById('receiptModal')).hide();


    }

    // 7. FINALIZAR ORDEN (ENVIAR A BASE DE DATOS)
    function finishOrder() {
        if (cart.length === 0) return;

        // Calculamos el total real para enviar
        let total = 0;
        cart.forEach(item => total += (item.price * item.qty));

        // Enviamos los datos al servidor usando FETCH (AJAX)
        fetch("{{ route('pos.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // Token de seguridad obligatorio en Laravel
            },
            body: JSON.stringify({
                cart: cart,
                total: total
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si todo salió bien en el servidor:
                alert('¡Venta guardada y Stock actualizado!');
                
                // Limpiamos todo
                cart = [];
                renderCart();
                bootstrap.Modal.getInstance(document.getElementById('receiptModal')).hide();
                
                // Opcional: Recargar página para actualizar los stocks visuales
                location.reload(); 
            } else {
                alert('Hubo un error al guardar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión con el servidor.');
        });
    }
</script>


@endsection