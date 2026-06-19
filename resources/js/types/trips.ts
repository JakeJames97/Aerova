import type {Destination} from "@/types/destinations.ts";

export type TripStatus = 'planned' | 'in_progress' | 'completed';

export interface Trip {
  id: string;
  name: string;
  description: string | null;
  start_date: string;
  end_date: string;
  status: TripStatus;
  created_at: string;
  destinations_count: number;
  destinations?: Destination[];
}

export interface TripPayload {
  name: string;
  description: string | null;
  start_date: string;
  end_date: string;
  status: TripStatus;
}
