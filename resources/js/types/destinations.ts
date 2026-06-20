import type {Task} from "@/types/tasks.ts";
import type {TripStatus} from "@/types/trips.ts";

export interface Destination {
  id: string;
  city: string;
  budget: number;
  country_code: string;
  arrival_date: string | null;
  departure_date: string | null;
  tasks: Task[];
}

export interface DestinationPayload {
  city: string;
  budget: number;
  country_code: string;
  arrival_date: string;
  departure_date: string;
}
