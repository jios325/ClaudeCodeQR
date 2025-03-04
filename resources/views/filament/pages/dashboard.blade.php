<x-filament-panels::page>
    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getWidgetsColumns()"
    />
    
    <div class="grid gap-6 lg:grid-cols-2">
        <x-filament::section>
            <div class="text-xl font-bold mb-4">
                Bienvenido a QR Code Generator
            </div>
            <p class="mb-4">
                Desde este panel podrás gestionar todos tus códigos QR:
            </p>
            <ul class="list-disc ml-6 mb-4">
                <li>Crear códigos QR dinámicos que puedes actualizar cuando lo necesites</li>
                <li>Generar códigos QR estáticos para distintos tipos de información</li>
                <li>Monitorear las estadísticas de escaneo de tus códigos</li>
                <li>Administrar los usuarios del sistema</li>
            </ul>
            <p>
                Para comenzar, selecciona una opción del menú lateral.
            </p>
        </x-filament::section>
    </div>
    
    <x-filament-widgets::widgets
        :widgets="$this->getFooterWidgets()"
        :columns="$this->getWidgetsColumns()"
    />
</x-filament-panels::page>
