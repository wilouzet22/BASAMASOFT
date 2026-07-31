import math

start_x = 986.5
start_y = 1084.0
end_x = 969.3
end_y = 252.0
num_points = 40

# We want a slight curve. We'll use a sine wave that goes from 0 to pi.
# sin(0) = 0, sin(pi) = 0. Peak is at pi/2.
# Let's add a max amplitude of 80 pixels.

print("$refPoints = [")
for i in range(num_points):
    t = i / (num_points - 1) # 0.0 to 1.0
    
    # Linear interpolation for Y (bottom to top)
    y = start_y + (end_y - start_y) * t
    
    # Linear interpolation for X + slight curve
    base_x = start_x + (end_x - start_x) * t
    
    # curve offset
    curve_offset = math.sin(t * math.pi) * 80 # max 80 px offset to the right
    
    x = base_x + curve_offset
    
    label = "Base" if i == 0 else ("Cima" if i == num_points - 1 else "")
    comment = f"// Semana {i+1}" + (f" ({label})" if label else "")
    
    print(f"    {i+1:<2} => ['x' => {x:.2f}, 'y' => {y:.2f}], {comment}")
print("];")
