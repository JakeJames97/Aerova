import type {Task} from "@/types/tasks.ts";
import type {Transport} from "@/types/transports.ts";

export interface Destination {
  id: string;
  city: string;
  budget: number;
  budget_formatted: string;
  converted_budget_formatted: string;
  country_code: string;
  arrival_date: string | null;
  departure_date: string | null;
  tasks: Task[];
  transports: Transport[];
}

export interface DestinationPayload {
  city: string;
  budget: number;
  country_code: string;
  arrival_date: string;
  departure_date: string;
}
