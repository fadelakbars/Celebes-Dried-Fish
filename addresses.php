<?php
require_once 'config/database.php';
check_login();

$user_id = $_SESSION['user_id'];
$error_msg = "";
$success_msg = "";

// Helper to reset other defaults if setting this one to default
function reset_other_defaults($conn, $user_id, $exclude_id = 0) {
    $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ? AND id != ?");
    $stmt->bind_param("ii", $user_id, $exclude_id);
    $stmt->execute();
    $stmt->close();
}

// Helper to ensure at least one address is default if any exist
function ensure_default_exists($conn, $user_id) {
    // Check if there is a default
    $stmt = $conn->prepare("SELECT id FROM user_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $has_default = ($res->num_rows > 0);
    $stmt->close();

    if (!$has_default) {
        // Set the oldest address as default
        $stmt2 = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE user_id = ? ORDER BY created_at ASC LIMIT 1");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();
    }
}

// Handle POST actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // 1. ADD ADDRESS
        if ($action == 'add') {
            $label = sanitize_input($_POST['label']);
            $receiver_name = sanitize_input($_POST['receiver_name']);
            $receiver_phone = sanitize_input($_POST['receiver_phone']);
            $address = sanitize_input($_POST['address']);
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            if (empty($label) || empty($receiver_name) || empty($receiver_phone) || empty($address)) {
                $error_msg = "Semua field wajib diisi!";
            } else {
                // If it is the first address, automatically make it default
                $check_first = $conn->prepare("SELECT id FROM user_addresses WHERE user_id = ? LIMIT 1");
                $check_first->bind_param("i", $user_id);
                $check_first->execute();
                $first_res = $check_first->get_result();
                if ($first_res->num_rows == 0) {
                    $is_default = 1;
                }
                $check_first->close();

                $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, label, receiver_name, receiver_phone, address, is_default) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssi", $user_id, $label, $receiver_name, $receiver_phone, $address, $is_default);
                
                if ($stmt->execute()) {
                    $new_id = $conn->insert_id;
                    if ($is_default == 1) {
                        reset_other_defaults($conn, $user_id, $new_id);
                    }
                    $success_msg = "Alamat baru berhasil ditambahkan.";
                } else {
                    $error_msg = "Gagal menambahkan alamat.";
                }
                $stmt->close();
            }
        }

        // 2. EDIT ADDRESS
        elseif ($action == 'edit') {
            $address_id = (int)$_POST['address_id'];
            $label = sanitize_input($_POST['label']);
            $receiver_name = sanitize_input($_POST['receiver_name']);
            $receiver_phone = sanitize_input($_POST['receiver_phone']);
            $address = sanitize_input($_POST['address']);
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            if (empty($label) || empty($receiver_name) || empty($receiver_phone) || empty($address)) {
                $error_msg = "Semua field wajib diisi!";
            } else {
                $stmt = $conn->prepare("UPDATE user_addresses SET label = ?, receiver_name = ?, receiver_phone = ?, address = ?, is_default = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("ssssiii", $label, $receiver_name, $receiver_phone, $address, $is_default, $address_id, $user_id);
                
                if ($stmt->execute()) {
                    if ($is_default == 1) {
                        reset_other_defaults($conn, $user_id, $address_id);
                    } else {
                        ensure_default_exists($conn, $user_id);
                    }
                    $success_msg = "Alamat berhasil diperbarui.";
                } else {
                    $error_msg = "Gagal memperbarui alamat.";
                }
                $stmt->close();
            }
        }

        // 3. DELETE ADDRESS
        elseif ($action == 'delete') {
            $address_id = (int)$_POST['address_id'];
            
            $stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $address_id, $user_id);
            if ($stmt->execute()) {
                ensure_default_exists($conn, $user_id);
                $success_msg = "Alamat berhasil dihapus.";
            } else {
                $error_msg = "Gagal menghapus alamat.";
            }
            $stmt->close();
        }

        // 4. SET DEFAULT
        elseif ($action == 'set_default') {
            $address_id = (int)$_POST['address_id'];
            
            $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $address_id, $user_id);
            if ($stmt->execute()) {
                reset_other_defaults($conn, $user_id, $address_id);
                $success_msg = "Alamat utama berhasil diperbarui.";
            } else {
                $error_msg = "Gagal mengatur alamat utama.";
            }
            $stmt->close();
        }
    }
}

// Fetch all addresses
$stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses_result = $stmt->get_result();
$addresses = [];
while ($row = $addresses_result->fetch_assoc()) {
    $addresses[] = $row;
}
$stmt->close();

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div style="background-color: var(--primary-light); padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="color: var(--primary-color);"><i class="fas fa-map-marker-alt"></i> Alamat Saya</h1>
        <p>Kelola alamat pengiriman Anda untuk mempermudah proses checkout</p>
    </div>
</div>

<section style="padding: 60px 0; background-color: #f9f9f9; min-height: 60vh;">
    <div class="container" style="max-width: 900px;">
        
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: var(--text-dark); margin: 0; font-size: 1.5rem;">Daftar Alamat</h2>
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Tambah Alamat Baru
            </button>
        </div>

        <?php if (empty($addresses)): ?>
            <div style="background: white; padding: 50px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; color: #666;">
                <i class="fas fa-map-marked-alt" style="font-size: 4em; margin-bottom: 20px; color: #ccc;"></i>
                <p style="font-size: 1.1rem; margin-bottom: 20px;">Anda belum menyimpan alamat pengiriman.</p>
                <button class="btn btn-primary" onclick="openAddModal()">Tambah Alamat Sekarang</button>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 20px;">
                <?php foreach ($addresses as $addr): ?>
                    <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); border-left: 5px solid <?php echo $addr['is_default'] ? 'var(--primary-color)' : '#eee'; ?>; display: flex; justify-content: space-between; align-items: flex-start; transition: var(--transition);">
                        
                        <div style="flex: 1; padding-right: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                                <strong style="font-size: 1.1rem; color: var(--text-dark);"><?php echo htmlspecialchars($addr['receiver_name']); ?></strong>
                                <span style="color: var(--text-light); font-size: 0.95rem;">| <?php echo htmlspecialchars($addr['receiver_phone']); ?></span>
                                <span style="background: #e8f4fd; color: #1e88e5; padding: 3px 8px; border-radius: 3px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($addr['label']); ?></span>
                                <?php if ($addr['is_default']): ?>
                                    <span style="background: var(--primary-light); color: var(--primary-color); padding: 3px 8px; border-radius: 3px; font-size: 0.75rem; font-weight: 600;">Utama</span>
                                <?php endif; ?>
                            </div>
                            <p style="color: var(--text-light); margin: 0; line-height: 1.6; font-size: 0.95rem; white-space: pre-line;"><?php echo htmlspecialchars($addr['address']); ?></p>
                        </div>

                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px; min-width: 120px;">
                            <div style="display: flex; gap: 10px;">
                                <button class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($addr)); ?>)">
                                    <i class="fas fa-edit"></i> Ubah
                                </button>
                                <?php if (!$addr['is_default']): ?>
                                    <form action="addresses.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');" style="margin: 0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
                                        <button type="submit" class="btn" style="background: #ffebee; color: #c62828; padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <?php if (!$addr['is_default']): ?>
                                <form action="addresses.php" method="POST" style="margin: 0; width: 100%;">
                                    <input type="hidden" name="action" value="set_default">
                                    <input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
                                    <button type="submit" class="btn btn-outline btn-block" style="padding: 6px 10px; font-size: 0.8rem; border-color: #bbb; color: #555;">
                                        Atur Sebagai Utama
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Address Modal (Add / Edit) -->
<div id="addressModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle" style="color: var(--primary-color); margin-bottom: 20px;">Tambah Alamat Baru</h3>
        
        <form action="addresses.php" method="POST" id="addressForm">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="address_id" id="formAddressId" value="">

            <div class="input-group">
                <label for="label">Label Alamat (cth: Rumah, Kantor)</label>
                <input type="text" id="label" name="label" placeholder="Rumah / Kantor / Kos" required>
            </div>

            <div class="form-grid" style="margin-bottom: 20px;">
                <div class="input-group" style="margin-bottom: 0;">
                    <label for="receiver_name">Nama Penerima</label>
                    <input type="text" id="receiver_name" name="receiver_name" required>
                </div>
                <div class="input-group" style="margin-bottom: 0;">
                    <label for="receiver_phone">Nomor HP</label>
                    <input type="text" id="receiver_phone" name="receiver_phone" required>
                </div>
            </div>

            <div class="input-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label for="address" style="margin: 0;">Alamat Lengkap</label>
                    <button type="button" onclick="openMapPicker()" style="background: none; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 4px 10px; border-radius: 5px; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-map-marked-alt"></i> Pilih dari Peta
                    </button>
                </div>
                <textarea id="address" name="address" rows="3" placeholder="Nama jalan, No. Rumah, Kecamatan, Kota, Provinsi — atau pilih dari peta" required></textarea>
            </div>

            <div class="input-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="is_default" name="is_default" style="width: auto; cursor: pointer;">
                <label for="is_default" style="margin: 0; cursor: pointer; font-weight: normal; font-size: 0.95rem;">Jadikan Alamat Utama</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">Simpan Alamat</button>
        </form>
    </div>
</div>
<!-- Map Picker Modal -->
<div id="mapPickerModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.65); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; width:90%; max-width:700px; max-height:90vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <!-- Header -->
        <div style="padding:18px 22px; background:var(--primary-color); color:#fff; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
            <div>
                <h3 style="margin:0; font-size:1.1rem;"><i class="fas fa-map-marked-alt"></i> Pilih Lokasi dari Peta</h3>
                <p style="margin:4px 0 0; font-size:0.8rem; opacity:0.85;">Klik lokasi di peta atau cari nama jalan / tempat</p>
            </div>
            <button onclick="closeMapPicker()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:1.1rem; display:flex; align-items:center; justify-content:center;">&times;</button>
        </div>

        <!-- Search bar -->
        <div style="padding:12px 16px; background:#f9f3ef; border-bottom:1px solid #eee; flex-shrink:0;">
            <div style="display:flex; gap:8px;">
                <input id="mapSearchInput" type="text" placeholder="Cari alamat atau nama tempat..." style="flex:1; padding:10px 14px; border:1px solid #ddd; border-radius:6px; font-size:0.9rem; outline:none;" onkeydown="if(event.key==='Enter'){event.preventDefault();searchLocation();}">
                <button onclick="searchLocation()" style="background:var(--primary-color); color:#fff; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; font-weight:600; font-size:0.9rem; white-space:nowrap;"><i class="fas fa-search"></i> Cari</button>
                <button onclick="useMyLocation()" title="Gunakan lokasi saya" style="background:#1a73e8; color:#fff; border:none; padding:10px 13px; border-radius:6px; cursor:pointer; font-size:0.9rem;"><i class="fas fa-crosshairs"></i></button>
            </div>
            <div id="mapSearchResults" style="margin-top:6px; display:none; background:#fff; border:1px solid #ddd; border-radius:6px; max-height:160px; overflow-y:auto;"></div>
        </div>

        <!-- Map -->
        <div id="leafletMap" style="flex:1; min-height:320px;"></div>

        <!-- Selected address preview -->
        <div style="padding:14px 16px; background:#f9f9f9; border-top:1px solid #eee; flex-shrink:0;">
            <div style="display:flex; align-items:flex-start; gap:10px;">
                <i class="fas fa-map-marker-alt" style="color:var(--primary-color); margin-top:3px; flex-shrink:0;"></i>
                <div style="flex:1;">
                    <p style="margin:0; font-size:0.8rem; color:#888; margin-bottom:3px;">Alamat yang dipilih:</p>
                    <p id="mapSelectedAddress" style="margin:0; font-size:0.9rem; color:#333; font-weight:500;">Klik lokasi di peta untuk mendapatkan alamat</p>
                </div>
            </div>
            <button id="mapConfirmBtn" onclick="confirmMapAddress()" disabled style="margin-top:12px; width:100%; background:var(--primary-color); color:#fff; border:none; padding:12px; border-radius:8px; cursor:pointer; font-size:0.95rem; font-weight:600; opacity:0.5;"><i class="fas fa-check"></i> Gunakan Alamat Ini</button>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<script>
const modal = document.getElementById('addressModal');
const modalTitle = document.getElementById('modalTitle');
const formAction = document.getElementById('formAction');
const formAddressId = document.getElementById('formAddressId');
const form = document.getElementById('addressForm');

// Modal fields
const labelInput = document.getElementById('label');
const receiverNameInput = document.getElementById('receiver_name');
const receiverPhoneInput = document.getElementById('receiver_phone');
const addressInput = document.getElementById('address');
const isDefaultInput = document.getElementById('is_default');

function openAddModal() {
    modalTitle.innerText = "Tambah Alamat Baru";
    formAction.value = "add";
    formAddressId.value = "";
    form.reset();
    isDefaultInput.checked = false;
    isDefaultInput.disabled = false;
    modal.style.display = 'block';
}

function openEditModal(addr) {
    modalTitle.innerText = "Ubah Alamat";
    formAction.value = "edit";
    formAddressId.value = addr.id;
    
    labelInput.value = addr.label;
    receiverNameInput.value = addr.receiver_name;
    receiverPhoneInput.value = addr.receiver_phone;
    addressInput.value = addr.address;
    
    isDefaultInput.checked = addr.is_default == 1;
    if (addr.is_default == 1) {
        isDefaultInput.disabled = true;
    } else {
        isDefaultInput.disabled = false;
    }
    
    modal.style.display = 'block';
}

function closeModal() {
    modal.style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById('mapPickerModal')) closeMapPicker();
}

// ============================================================
// MAP PICKER (Leaflet.js + OpenStreetMap, no API key needed)
// ============================================================
let leafletMap = null;
let mapMarker = null;
let selectedMapAddress = '';

function openMapPicker() {
    const mapModal = document.getElementById('mapPickerModal');
    mapModal.style.display = 'flex';

    // Initialize map only once
    if (!leafletMap) {
        // Default center: Makassar, Sulawesi Selatan
        leafletMap = L.map('leafletMap').setView([-5.1477, 119.4327], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(leafletMap);

        leafletMap.on('click', function(e) {
            placeMarker(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    }

    // Force Leaflet to recalculate size after modal display
    setTimeout(() => leafletMap.invalidateSize(), 150);

    // Pre-fill search with current address value
    const current = addressInput.value.trim();
    if (current) document.getElementById('mapSearchInput').value = current;
}

function closeMapPicker() {
    document.getElementById('mapPickerModal').style.display = 'none';
    document.getElementById('mapSearchResults').style.display = 'none';
}

function placeMarker(lat, lng) {
    if (mapMarker) {
        mapMarker.setLatLng([lat, lng]);
    } else {
        mapMarker = L.marker([lat, lng], { draggable: true }).addTo(leafletMap);
        mapMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });
    }
    leafletMap.setView([lat, lng], 17);
}

function reverseGeocode(lat, lng) {
    const addrEl = document.getElementById('mapSelectedAddress');
    const btn = document.getElementById('mapConfirmBtn');
    addrEl.innerText = 'Mendapatkan alamat...';
    btn.disabled = true;
    btn.style.opacity = '0.5';

    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`)
        .then(r => r.json())
        .then(data => {
            const addr = data.display_name || '';
            selectedMapAddress = addr;
            addrEl.innerText = addr || 'Alamat tidak ditemukan di lokasi ini.';
            btn.disabled = !addr;
            btn.style.opacity = addr ? '1' : '0.5';
        })
        .catch(() => {
            addrEl.innerText = 'Gagal mendapatkan alamat. Periksa koneksi internet.';
        });
}

function searchLocation() {
    const query = document.getElementById('mapSearchInput').value.trim();
    if (!query) return;
    const resultsEl = document.getElementById('mapSearchResults');
    resultsEl.style.display = 'block';
    resultsEl.innerHTML = '<div style="padding:10px; color:#888; font-size:0.85rem;">Mencari...</div>';

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=6&accept-language=id&countrycodes=id`)
        .then(r => r.json())
        .then(results => {
            if (!results.length) {
                resultsEl.innerHTML = '<div style="padding:10px; color:#888; font-size:0.85rem;">Tidak ada hasil ditemukan.</div>';
                return;
            }
            resultsEl.innerHTML = results.map(r => `
                <div onclick="pickSearchResult(${r.lat}, ${r.lon})" style="padding:10px 14px; cursor:pointer; border-bottom:1px solid #f0f0f0; font-size:0.85rem; line-height:1.4;" onmouseover="this.style.background='#f9f3ef'" onmouseout="this.style.background='#fff'">
                    <i class='fas fa-map-marker-alt' style='color:var(--primary-color); margin-right:6px;'></i>${r.display_name}
                </div>`).join('');
        })
        .catch(() => {
            resultsEl.innerHTML = '<div style="padding:10px; color:#c00; font-size:0.85rem;">Gagal mencari. Periksa koneksi internet.</div>';
        });
}

function pickSearchResult(lat, lon) {
    document.getElementById('mapSearchResults').style.display = 'none';
    const numLat = parseFloat(lat);
    const numLon = parseFloat(lon);
    placeMarker(numLat, numLon);
    reverseGeocode(numLat, numLon);
}

function useMyLocation() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung geolokasi.');
        return;
    }
    navigator.geolocation.getCurrentPosition(
        pos => {
            placeMarker(pos.coords.latitude, pos.coords.longitude);
            reverseGeocode(pos.coords.latitude, pos.coords.longitude);
        },
        () => alert('Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi sudah diberikan di browser.')
    );
}

function confirmMapAddress() {
    if (selectedMapAddress) {
        addressInput.value = selectedMapAddress;
        closeMapPicker();
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>

