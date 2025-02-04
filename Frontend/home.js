// Get the element with the ID "image-track" from the DOM and assign it to the 'track' constant.
const track = document.getElementById("image-track");

// Define a constant for slide friction, which controls how fast or slow the sliding effect will be.
const slideFriction = 1;
const imageMoveFactor = 1.0; // New factor to reduce image internal transition movement.

// Event listener for when the mouse is pressed down.
window.onmousedown = e => {
    // Store the initial position of the mouse when clicked (clientX gives the horizontal position of the mouse).
    track.dataset.mouseDownAt = e.clientX;
}

// Event listener for when the mouse is moved.
window.onmousemove = e => {

    // If the mouse is not currently being pressed down (i.e., mouseDownAt is "0"), exit the function.
    if (track.dataset.mouseDownAt === "0") return;

    // Calculate the difference between the current mouse position and the initial mouse down position.(subtract current position from starting point)
    const mouseDelta = parseFloat(track.dataset.mouseDownAt) - e.clientX,
    
    // Calculate the maximum possible movement based on the width of the window and the slide friction.
    maxDelta = window.innerWidth * slideFriction;

    // Calculate the percentage of movement based on mouseDelta and maxDelta.(percentage of our slider that has been slid)
    const percentage = - (mouseDelta / maxDelta) * 100,

    // Add the calculated percentage to the previous percentage to get the new percentage of movement.
    nextPercentageUnconstrained = parseFloat(track.dataset.prevPercentage) + percentage,

    // Ensure the next percentage value stays within the range of 0 to -100.
    nextPercentage = Math.max(Math.min(nextPercentageUnconstrained, 0), -100);

    // Store the new percentage value in the dataset.
    track.dataset.percentage = nextPercentage;

    // Animate the movement of the track element (the image track) based on the percentage of movement.
    track.animate(
        {
            // Move the track horizontally based on the calculated nextPercentage, keeping the vertical position fixed at -50%.
            transform: `translate(${nextPercentage}%, -50%)`
        }, 
        {
            // Set the duration of the animation to 1200ms and ensure it remains in the final position.
            duration: 1200, fill: "forwards"
        }
    )

    // Loop through all the images within the "image-track" element.
    for (const image of track.getElementsByClassName("image")){
        // Adjust the internal movement of the images by reducing the amount of movement via imageMoveFactor.
        const imageMovement = nextPercentage * imageMoveFactor;
        // Animate the object position of each image based on the new percentage, ensuring they move along with the track.
        image.animate(
            {
                // Adjust the object position of the image to ensure it moves horizontally.
                objectPosition:  `${imageMovement + 100}% 50% `
            }, 
            {
                // Set the animation duration to match the track's animation and ensure the final position is kept.
                duration: 1200, fill: "forwards"
            }
        )
    }
}

// Event listener for when the mouse button is released.
window.onmouseup = () => {
    // Reset the mouse down position to 0, indicating that the mouse is no longer pressed.
    track.dataset.mouseDownAt = 0;
    
    // Store the current percentage as the previous percentage, allowing the next drag to continue from the last position.
    track.dataset.prevPercentage = track.dataset.percentage;
}