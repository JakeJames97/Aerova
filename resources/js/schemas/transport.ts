import * as yup from 'yup';

export const transportSchema = yup.object({
  type: yup.string().oneOf(['flight', 'train', 'car', 'other']).required(),
  from: yup.string().required('From is required').max(255),
  to: yup.string().required('To is required').max(255),
  identifier: yup.string().nullable().max(50),
  departure_at: yup.string().required('Departure is required'),
  arrival_at: yup
    .string()
    .required('Arrival is required')
    .test('after-departure', 'Arrival must be after departure', function (value) {
      return !value || !this.parent.departure_at || value >= this.parent.departure_at;
    }),
  price: yup.number().min(0).required('Price is required'),
  airline: yup.string().nullable().max(255),
  from_iata: yup.string().nullable().length(3),
  to_iata: yup.string().nullable().length(3),
});
