<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-tennis-green relative overflow-hidden">
        <!-- Fondo de cancha de tenis decorativo -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute inset-0 border-[10px] border-white/30 m-8"></div>
            <div class="absolute top-1/2 left-0 w-full h-1 bg-white/30 -translate-y-1/2"></div>
            <div class="absolute top-0 left-1/2 w-1 h-full bg-white/30 -translate-x-1/2"></div>
        </div>
        
        <div class="z-10">
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border-b-8 border-tennis-cyan z-10">
            <slot />
        </div>
    </div>
</template>
