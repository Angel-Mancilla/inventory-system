<template>

  <div class="relative w-full overflow-auto rounded-lg border">
    
    <Table class="w-full caption-bottom text-sm">
      
      <TableHeader class="[&_tr]:border-b bg-muted sticky top-0 z-10">
        <TableRow 
          v-for="headerGroup in table.getHeaderGroups()" 
          :key="headerGroup.id"
          class="hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors"
        >
          <TableHead 
            v-for="header in headerGroup.headers" 
            :key="header.id"
            :style="{ width: header.column.getSize() !== 150 ? header.column.getSize() + 'px' : 'auto' }"
            class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap [&:has([role=checkbox])]:pr-0"
          >
            <FlexRender 
              v-if="!header.isPlaceholder"
              :render="header.column.columnDef.header" 
              :props="header.getContext()" 
            />
          </TableHead>
        </TableRow>
      </TableHeader>

      <TableBody class="[&_tr:last-child]:border-0">
        <TableRow 
          v-for="row in table.getRowModel().rows" 
          :key="row.id"
          :data-state="row.getIsSelected() ? 'selected' : undefined"
          class="hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors relative z-0"
        >

          <TableCell 
            v-for="cell in row.getVisibleCells()" 
            :key="cell.id"
            :style="{ width: cell.column.getSize() !== 150 ? cell.column.getSize() + 'px' : 'auto' }"
            class="p-2 align-middle whitespace-nowrap [&:has([role=checkbox])]:pr-0"
          >
            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
          </TableCell>
        </TableRow>
      </TableBody>

    </Table>
  </div>
</template>

<script setup lang="ts" generic="TData extends any, TValue">
import {
  FlexRender,
  getCoreRowModel,
  useVueTable,
  type ColumnDef,
} from '@tanstack/vue-table'
import {
  Table, TableBody, TableCell, TableHead,
  TableHeader, TableRow,
} from '@/components/ui/table'

const props = defineProps<{
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
}>()

const table = useVueTable({
  get data() { return props.data },
  get columns() { return props.columns },
  getCoreRowModel: getCoreRowModel(),
})
</script>