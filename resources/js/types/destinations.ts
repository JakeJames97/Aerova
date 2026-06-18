import type {Task} from "@/types/task.ts";

export interface Destination {
  id: string;
  name: string;
  arrival_date: string | null;
  departure_date: string | null;
  sort_order: number;
  tasks: Task[];
}
