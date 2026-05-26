                ->name('orders.show');

            Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
                ->name('orders.updatePayment');

            Route::post('/orders/{order}/void', [OrderController::class, 'void'])
                ->name('orders.void');

            Route::get('/cashier-sessions', [CashierSessionController::class, 'index'])
                ->name('cashier-sessions.index');

            Route::post('/cashier-sessions/open', [CashierSessionController::class, 'open'])
                ->name('cashier-sessions.open');

            Route::post('/cashier-sessions/{session}/close', [CashierSessionController::class, 'close'])
                ->name('cashier-sessions.close');