<template>
  <div class="flex items-center justify-between px-2 py-4">
    <!-- Muestra el total de registros -->
    <p v-if="total !== undefined" class="text-sm text-muted-foreground">
      Mostrando {{ from }}–{{ to }} de {{ total }}
    </p>

    <!-- Componente principal de paginación -->
    <Pagination :total="total || 0" :items-per-page="perPage || 10">
      <PaginationContent class="space-x-7">
        <template v-for="(link, index) in links" :key="index">
          
          <!-- Botón de "Anterior" -->
          <PaginationItem v-if="index === 0">
            <PaginationPrevious
              href="#"
              :class="!link.url ? 'pointer-events-none opacity-50' : ''"
              @click.prevent="visit(link.url)"
            />
          </PaginationItem>

          <!-- Botón de "Siguiente" -->
          <PaginationItem v-else-if="index === links.length - 1">
            <PaginationNext
              href="#"
              :class="!link.url ? 'pointer-events-none opacity-50' : ''"
              @click.prevent="visit(link.url)"
            />
          </PaginationItem>

          <!-- Separador de puntos suspensivos (...) -->
          <PaginationItem v-else-if="link.label === '...'">
            <PaginationEllipsis />
          </PaginationItem>

          <!-- Números de página usando Button dentro de PaginationItem -->
          <PaginationItem v-else>
            <Button
              :variant="link.active ? 'default' : 'outline'"
              size="icon"
              class="w-9 h-9"
              :disabled="!link.url"
              @click.prevent="visit(link.url)"
              v-html="link.label"
            />
          </PaginationItem>

        </template>
      </PaginationContent>
    </Pagination>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'

interface InertiaPaginationLink {
  url: string | null
  label: string
  active: boolean
}

defineProps<{
  links: InertiaPaginationLink[]
  from?: number
  to?: number
  total?: number
  perPage?: number
}>()

// Función para navegar sin recargar la página
function visit(url: string | null) {
  if (!url) return

  router.visit(url, {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>
<!-- <template>
  <div class="flex items-center justify-between px-2 py-4">
    <p v-if="total !== undefined" class="text-sm text-muted-foreground">
      Mostrando {{ from }}–{{ to }} de {{ total }}
    </p>

    <div class="flex gap-1">
      <Button
        v-for="(link, index) in links"
        :key="index"
        variant="outline"
        size="sm"
        :disabled="!link.url"
        :class="{ 'bg-accent': link.active }"
        @click="visit(link.url)"
        v-html="link.label"
      />
      
    </div>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'

interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

defineProps<{
  links: PaginationLink[]
  from?: number
  to?: number
  total?: number
}>()

function visit(url: string | null) {
  if (!url) return

  router.visit(url, {
    preserveState: true,
    preserveScroll: true,
  })
}
</script> -->