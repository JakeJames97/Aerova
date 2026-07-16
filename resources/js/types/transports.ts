export type TransportType = 'flight' | 'train' | 'car' | 'other';

export interface Transport {
  id: string;
  type: TransportType;
  from: string;
  to: string;
  identifier: string | null;
  departure_at: string;
  arrival_at: string;
  price: number;
  price_formatted: string;
  airline: string | null;
  from_iata: string | null;
  to_iata: string | null;
  current_price: number | null;
  current_price_formatted: string | null;
  price_checked_at: string | null;
}

export interface TransportPayload {
  type: TransportType;
  from: string;
  to: string;
  identifier: string | null;
  departure_at: string;
  arrival_at: string;
  price: number;
  airline: string | null;
  from_iata: string | null;
  to_iata: string | null;
}
