<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

withDefaults(
  defineProps<{
    open: boolean;
    title: string;
    description?: string;
    formId: string;
    processing?: boolean;
    submitLabel?: string;
  }>(),
  {
    processing: false,
    submitLabel: 'Guardar',
  }
);

const emit = defineEmits<{
  'update:open': [value: boolean];
}>();
</script>

<template>
  <Dialog :open="open" @update:open="emit('update:open', $event)">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ title }}</DialogTitle>
        <DialogDescription v-if="description">{{ description }}</DialogDescription>
      </DialogHeader>

      <!-- Aquí va el <form> completo de cada entidad -->
      <slot />

      <DialogFooter>
        <slot name="footer">
          <Button type="submit" :form="formId" :disabled="processing">
            {{ submitLabel }}
          </Button>
        </slot>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>