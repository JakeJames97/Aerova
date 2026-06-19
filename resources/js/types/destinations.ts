import type {Task} from "@/types/tasks.ts";
import type {TripStatus} from "@/types/trips.ts";

export interface Destination {
  id: string;
  name: string;
  arrival_date: string | null;
  departure_date: string | null;
  tasks: Task[];
}

export interface DestinationPayload {
  name: string;
  arrival_date: string;
  departure_date: string;
}
