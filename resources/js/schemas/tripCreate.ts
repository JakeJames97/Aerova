import * as yup from 'yup';
import { TRIP_STATUSES } from '@/types/trips';

export const tripStepSchema = yup.object({
  name: yup.string().required('Name is required').max(255),
  description: yup.string().nullable(),
  start_date: yup.string().required('Start date is required'),
  end_date: yup.string().required('End date is required')
    .test('after-start', 'End date must be after the start date', function (value) {
      return !this.parent.start_date || !value || value >= this.parent.start_date;
    }),
  status: yup.string().oneOf(TRIP_STATUSES).required(),
});

export const destinationsStepSchema = yup.object({
  destinations: yup.array().of(
    yup.object({
      city: yup.string().required('City is required').max(255),
      country_code: yup.string().required('Country is required'),
      budget: yup.number().required('Budget is required').min(0),
      arrival_date: yup.string().required('Arrival date is required'),
      departure_date: yup.string().required('Departure date is required')
        .test('after-arrival', 'Departure must be after arrival', function (value) {
          return !this.parent.arrival_date || !value || value >= this.parent.arrival_date;
        }),
    }),
  ),
});

export const reviewStepSchema = yup.object({});
