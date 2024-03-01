// Sample data structure to represent available seats
let availableSeats = [
  { seatNumber: 'A1', booked: false },
  { seatNumber: 'A2', booked: false },
  { seatNumber: 'B1', booked: false },
  { seatNumber: 'B2', booked: false },
  // ... more seats
];

function displaySeatMap(containerId, seats) {
  const container = document.getElementById(containerId);
  container.innerHTML = '';

  seats.forEach(seat => {
    const seatDiv = document.createElement('div');
    seatDiv.className = seat.booked ? 'seat booked' : 'seat';
    seatDiv.textContent = seat.seatNumber;
    seatDiv.addEventListener('click', () => selectSeat(seat));

    container.appendChild(seatDiv);
  });
}

function selectSeat(seat) {
  if (!seat.booked) {
    seat.booked = true;
    // Add logic to visually indicate the selected seat
    // For example, you can change the background color of the selected seat
  }
}

function showAvailableSeats() {
  const selectedDate = document.getElementById('datePicker').value;
  const selectedTime = document.getElementById('timePicker').value;

  // Filter available seats based on the selected date and time
  const filteredSeats = availableSeats.filter(seat => !seat.booked);

  displaySeatMap('seatMap', filteredSeats);
}

// Call the function to initially display available seats
showAvailableSeats();